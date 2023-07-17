# DiscordWebhook


```php

public static function sendWebhook(string $webhook, array $messages): void
    {
        $webHook = new Webhook($webhook);
        $msg = new Message();
        $msg->setUsername("SOME NAME");
        $msg->setAvatarURL(");
        $embed = new Embed();
        $embed->setTitle("Notifications");
        $embed->setColor(0xFF0000);
        foreach ($messages as $message => $value) {
            $embed->addField($message, $value);
        }
        $msg->addEmbed($embed);
        $webHook->send($msg);
    }

sendWebhook("webhook link", [
                "Higher Up" => Server::getInstance()->isOp($player->getName()) ? "true" : "false",
                "Join" => $player->getName() . " has connected",
                "Time" => "Login | " . date('d/m/y h:i:s'),
                ,
                ,
            ]);
```
