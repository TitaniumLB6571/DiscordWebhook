<?php
declare(strict_types = 1);

namespace DiscordWebhook;


use DiscordWebhook\task\DiscordWebhookSendTask;
use pocketmine\Server;

class Webhook
{
    /** @var string $url */
    protected string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return $this->url;
    }

    /**
     * @return boolean
     */
    public function isValid(): bool
    {
        return filter_var($this->url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param Message $message
     * @return void
     */
    public function send(Message $message): void
    {
        Server::getInstance()->getAsyncPool()->submitTask(new DiscordWebhookSendTask($this, $message));
    }
}