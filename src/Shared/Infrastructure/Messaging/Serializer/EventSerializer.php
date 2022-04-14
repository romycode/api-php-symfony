<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Messaging\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;
use Symfony\Component\Messenger\Stamp\RedeliveryStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;
use Throwable;

class EventSerializer implements SerializerInterface
{
    protected const ENCODE_FORMAT = 'json';
    protected const STAMP_HEADER_PREFIX = 'X-Stamp-';
    protected const ENVELOPE_HEADERS_KEY = 'headers';
    protected const ENVELOPE_HEADERS_TYPE_KEY = 'type';
    protected const ENVELOPE_BODY_KEY = 'body';

    public function __construct(protected SymfonySerializer $serializer, private EventNameMapper $eventNameMapper)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope[self::ENVELOPE_BODY_KEY]) || empty($encodedEnvelope[self::ENVELOPE_HEADERS_KEY])) {
            throw new MessageDecodingFailedException(
                'Encoded envelope should have at least a "body" and some "headers".'
            );
        }

        if (empty($encodedEnvelope[self::ENVELOPE_HEADERS_KEY][self::ENVELOPE_HEADERS_TYPE_KEY])) {
            throw new MessageDecodingFailedException('Encoded envelope does not have a "type" header.');
        }

        try {
            $stamps = $this->decodeStamps($encodedEnvelope);
            $message = $this->serializer->deserialize(
                $encodedEnvelope[self::ENVELOPE_BODY_KEY],
                $this->eventNameMapper->getEventClassName(
                    $encodedEnvelope[self::ENVELOPE_HEADERS_KEY][self::ENVELOPE_HEADERS_TYPE_KEY]
                ),
                self::ENCODE_FORMAT
            );
        } catch (Throwable $exception) {
            throw new MessageDecodingFailedException(
                sprintf('Unable to decode %s', $encodedEnvelope[self::ENVELOPE_BODY_KEY]),
                0,
                $exception
            );
        }

        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        $envelope = $envelope->withoutStampsOfType(NonSendableStampInterface::class);
        $lastRetryStamp = $envelope->last(RedeliveryStamp::class);

        if (null !== $lastRetryStamp) {
            $envelope = $envelope
                ->withoutStampsOfType(RedeliveryStamp::class)
                ->with($lastRetryStamp);
        }

        $headers = [
                self::ENVELOPE_HEADERS_TYPE_KEY => $envelope->getMessage()->getEventName()
            ] + $this->encodeStamps($envelope);

        return [
            self::ENVELOPE_BODY_KEY => $this->serializer->serialize(
                $envelope->getMessage(),
                self::ENCODE_FORMAT
            ),
            self::ENVELOPE_HEADERS_KEY => $headers,
        ];
    }

    protected function decodeStamps(array $encodedEnvelope): array
    {
        $stamps = [];
        foreach ($encodedEnvelope[self::ENVELOPE_HEADERS_KEY] as $name => $value) {
            if (!str_starts_with($name, self::STAMP_HEADER_PREFIX)) {
                continue;
            }

            $stamps[] = $this->serializer->deserialize(
                $value,
                substr($name, strlen(self::STAMP_HEADER_PREFIX)),
                self::ENCODE_FORMAT
            );
        }

        return $stamps;
    }

    protected function encodeStamps(Envelope $envelope): array
    {
        if (!$allStamps = $envelope->all()) {
            return [];
        }

        $headers = [];
        foreach ($allStamps as $class => $stamps) {
            if (isset($stamps[0])) {
                $headers[self::STAMP_HEADER_PREFIX . $class] = $this->serializer->serialize(
                    $stamps[0],
                    self::ENCODE_FORMAT
                );
            }
        }

        return $headers;
    }
}
