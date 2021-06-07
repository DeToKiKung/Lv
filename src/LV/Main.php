<?php

namespace LV;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this->getLogger()->info("Lv run");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function (int $_): void {
                //some code here to repeat every 20 ticks (1 second)
                foreach ($this->getServer()->getOnlinePlayers() as $p) {
                    $Player = $p->getPlayer();
                    $exp = $Player->getXpLevel();
                    $Player->setDisplayName("Lv $exp ".$Player->getName());
                   
                }
            }
        ), 20);
    }

    public function onCommand(CommandSender $s, Command $c, String $label, array $args): bool
    {
        switch ($c->getName()) {
            case "lv";
                $this->formlv($s);
                break;
        }
        return true;
    }

    public function formlv(Player $sender)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender,  int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case "0";
                    $this->shoplv($sender);
                    break;
                // case "1";
                //     $this->tradelv($sender);
                //     break;
                case "1";

                    break;
            }
        });
        $name = $sender->getName();
        $exp = $sender->getXpLevel();
        $form->setTitle("LV UI");
        $form->setContent("สวัสดี $name\nLV $exp");
        $form->addButton("ซื้อของ");
        // $form->addButton("แลกเปลื่ยนด้วยเลวล");
        $form->addButton("ออก");
        $form->sendToPlayer($sender);
        return $form;
    }
    public function shoplv(Player $sender)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender,  int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case "0";
                $exp = $sender->getXpLevel();
                if ($exp >= 1) {
                    $economy = EconomyAPI::getInstance();
                    $economy->addMoney($sender, 1000);
                    $sender->subtractXpLevels(1);
                    $sender->sendMessage("คุณใช้ 1 LV ซื้อ เงิน 1000 บาท");
                    $this->shoplv($sender);
                } else {
                    $sender->sendMessage("LV คุณมึงไม่พอนะ");
                    $this->formlv($sender);
                }

                    break;
            }
        });
        $name = $sender->getName();
        $exp = $sender->getXpLevel();
        $form->setTitle("LV UI");
        $form->setContent("สวัสดี $name\nLV $exp");
        $form->addButton("1 LV = 1000 บาท");
        $form->sendToPlayer($sender);
        return $form;
    }
    // public function tradelv(Player $sender)
    // {
    //     $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    //     $form = $api->createSimpleForm(function (Player $sender,  int $data = null) {
    //         $result = $data;
    //         if ($result === null) {
    //             return true;
    //         }
    //         switch ($result) {
    //             case "0";

    //                 break;
    //         }
    //     });
    //     $name = $sender->getName();
    //     $exp = $sender->getXpLevel();
    //     $form->setTitle("LV UI");
    //     $form->setContent("สวัสดี $name\nLV $exp");
    //     $form->addButton("");
    //     $form->sendToPlayer($sender);
    //     return $form;
    // }
}
