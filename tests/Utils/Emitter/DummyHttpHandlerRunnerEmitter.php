<?php

declare(strict_types=1);

namespace WoohooLabs\Harmony\Tests\Utils\Emitter;

use Psr\Http\Message\ResponseInterface;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;

class DummyHttpHandlerRunnerEmitter implements EmitterInterface
{
    /**
     * Emit a response.
     *
     * Emits a response, including status line, headers, and the message body,
     * according to the environment.
     *
     * Implementations of this method may be written in such a way as to have
     * side effects, such as usage of header() or pushing output to the
     * output buffer.
     *
     * Implementations MAY raise exceptions if they are unable to emit the
     * response; e.g., if headers have already been sent.
     *
     * @param ResponseInterface $response
     */
    public function emit(ResponseInterface $response): bool
    {
        echo "true";

        return true;
    }
}
