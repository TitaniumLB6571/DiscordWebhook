<?php

declare(strict_types = 1);

namespace DiscordWebhook\task;

use DiscordWebhook\Message;
use DiscordWebhook\Webhook;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;
use function curl_close;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function in_array;
use function json_encode;

class DiscordWebhookSendTask extends AsyncTask
{

    /** @var NonThreadSafeValue $webhook */
    protected NonThreadSafeValue $webhook;
    /** @var NonThreadSafeValue $message */
    protected NonThreadSafeValue $message;

    /**
     * @param Webhook $webhook
     * @param Message $message
     */
    public function __construct(Webhook $webhook, Message $message)
    {
        $this->webhook = new NonThreadSafeValue($webhook);
        $this->message = new NonThreadSafeValue($message);
    }

    /**
     * @return void
     */
    public function onRun(): void
    {
        $ch = curl_init($this->webhook->deserialize()->getURL());
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->message->deserialize()));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $this->setResult([curl_exec($ch), curl_getinfo($ch, CURLINFO_RESPONSE_CODE)]);
        curl_close($ch);
    }

    /**
     * @return void
     */
    public function onCompletion(): void
    {
        $response = $this->getResult();
        if (!in_array($response[1], [200, 204])) {
            Server::getInstance()->getLogger()->error("[DiscordWebhookAPI] Got error ({$response[1]}): " . $response[0]);
        }
    }
}
