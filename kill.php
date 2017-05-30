<?php
namespace entpvpc;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item as B;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\utils\Utils;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
define('ED', 1);
define('EA', 2);
define('PA', 3);
if (substr(PHP_VERSION, 0, 1) == '5') {
    define('p7', false);
    define('p5', true);
} else {
    define('p7', true);
    define('p5', false);
    echo 'php7';
}
function caonima($c)
{
    if (method_exists($c, 'setNameTagVisible')) {
        $c->setNameTagVisible(true);
    }
    if (method_exists($c, 'setNameTagAlwaysVisible')) {
        $c->setNameTagAlwaysVisible(true);
    }
}
class kill extends PluginBase implements Listener
{
    public $ct, $ggg;
    public $arr = [];
    private $ED = [];
    private $EA = [];
    private $PA = [];
    private $mod = [];
    private $locked = null;
    private $mb = [];
    public $cc = [];
    public $cache = [];
    public static $gg;
    public function initi()
    {
        $this->cc = $this->cf->getAll();
        foreach ($this->cc as $k => $v) {
            if ($k != 'points' && $k != 'default' && $k != 'rate' && $k != 'firstaa') {
                if (!isset($this->cache[$v['level']])) {
                    $this->cache[$v['level']] = [];
                }
                $this->cache[$v['level']][$k] = new Vector3($v['x'], $v['y'], $v['z']);
            }
        }
    }
    public function naive(\pocketmine\event\entity\EntityDespawnEvent $e)
    {
        $ev = $e->getEntity()->getId();
        if (($naive = $this->getN($ev)) == -1) {
            return;
        }
        unset($this->arr[$naive][$this->ggg]);
    }
    public static function geti()
    {
        return self::$gg;
    }
    public function lc($a, $b, $l)
    {
        if ($this->locked == null) {
            if (ctype_digit($b)) {
                return $a == $b;
            } else {
                return $l->getFolderName() == $b;
            }
        } else {
            return $l->getFolderName() == $this->locked;
        }
    }
    public function onload()
    {
        self::$gg = $this;
    }
    public function ggongji($n)
    {
        if ($this->gongji->exists($n)) {
            return intval(explode('@', $this->gongji->g($n))[0]);
        } else {
            $this->gongji->s($n, '1@1');
            $this->gongji->save();
            return 1;
        }
    }
    public function gfangyu($n)
    {
        if ($this->gongji->exists($n)) {
            return intval(explode('@', $this->gongji->g($n))[1]);
        } else {
            $this->gongji->s($n, '1@1');
            $this->gongji->save();
            return 1;
        }
    }
    private function callapi($arr, $arg)
    {
        foreach ($arr as $v) {
            call_user_func_array($v, $arg);
        }
    }
    public function regsvr($cb, $id)
    {
        switch ($id) {
            case ED:
                $this->ED[] = $cb;
                break;
            case EA:
                $this->EA[] = $cb;
                break;
            case PA:
                $this->PA[] = $cb;
                break;
        }
    }
    public function sb($key)
    {
        if ($key == "cpera2") {
            return 10010;
        }
    }
    private function getN($id)
    {
        foreach ($this->arr as $key => $arr) {
            foreach ($arr as $key1 => $value) {
                if ($value == $id) {
                    $this->ggg = $key1;
                    return $key;
                }
            }
        }
        return -1;
    }
    private function pt($et)
    {
        $a = $et->getLevel();
        $pt = new \pocketmine\level\particle\LavaParticle(new Vector3($et->x, $et->y, $et->z));
        $a->addParticle($pt);
        $a->addParticle($pt);
        $a->addParticle($pt);
        $a->addParticle($pt);
    }
    public function kusi(\pocketmine\event\entity\EntityDamageEvent $e)
    {
        if ($e instanceof EntityDamageByEntityEvent) {
            if (($p = $e->getDamager()) instanceof Player) {
                $et = $e->getEntity();
                if (($key = $this->getN($et->getId())) == -1) {
                    return;
                }
                if ($this->czdg == 0 and $p->isCreative()) {
                    $p->sendMessage('创造模式禁止打怪！');
                    $e->setCancelled(1);
                }
                if ($this->lz->get('粒子开关', 1) != 0) {
                    $this->pt($et);
                }
                $this->callapi($this->EA, array($e, $key));
                $e->setDamage($e->getDamage() - $this->gfangyu($key));
                $e->setDamage(max($e->getDamage(), 0));
                $et->setNameTag(str_replace('@', "\n", $this->cf->get($key)['name']) . "[" . 'HP:' . ($et->getHealth() - $e->getDamage()) . '/' . $et->getMaxHealth() . ']');
                caonima($et);
                return;
            }
            if (($et = $e->getEntity()) instanceof Player) {
                if ($this->getN($p->getId()) == -1) {
                    return;
                }
                $this->callapi($this->PA, array($e, $this->getN($p->getId())));
                $e->setDamage($e->getDamage() + $this->ggongji($this->getN($p->getId())));
                $e->setDamage(max($e->getDamage(), 0));
            }
        }
    }
    public function onCommand(CommandSender $s, Command $cd, $label, array $a)
    {
        try {
            $p = $s;
            if ($a[0] == 'clear') {
                $this->arr = [];
                return 1;
            }
            if (!$s->isOp()) {
                return 0;
            }
            if ($a[0] == 'lockl') {
                $this->lz->set('levellock', $s->level->getFolderName());
                $s->sendMessage('done!');
                $this->lz->save();
                return 1;
            }
            if ($a[0] == 'setmb') {
                $this->mb[$a[1]] = $a[2];
                $s->sendMessage('done');
                return 1;
            }
            if ($a[0] == 'clmb') {
                $this->mb = [];
                $s->sendMessage('done');
                return 1;
            }
            if ($a[0] == 'prmb') {
                $s->sendMessage(print_r($this->mb, true));
                return 1;
            }
            $po = $p->getPosition();
            $le = $po->getLevel()->getfoldername();
            $x = $po->getX();
            $y = $po->getY();
            $z = $po->getZ();
            $s->sendMessage("duang！，创建成功" . $a[0] . ':level:' . $le . ",xyz:" . $x . $y . $z);
            $def["dis"] = 4;
            $def["level"] = $le;
            $def["x"] = $x;
            $def["y"] = $y;
            $def["z"] = $z;
            $def["hp"] = 15;
            $def["name"] = "xxxx";
            $def["count"] = 2;
            $def["type"] = "Zombie";
            $def = array_merge($def, $this->mb);
            $this->cf->set($a[0], $def);
            $this->cf->set("points", array_merge($this->cf->get("points"), array($a[0])));
            $this->cf->save();
            $this->cf->reload();
            $this->initi();
        } catch (\Exception $e) {
            $s->sendMessage('卧槽你又把指令输错了，赶紧看介绍去!!!');
            $s->sendMessage($e->getMessage());
        }
    }
    private function dop($n, $c)
    {
        $list = [];
        if ($c->exists($n)) {
            $c = $c->g($n);
            $c = explode('@', $c);
            foreach ($c as $i) {
                $i = explode(':', $i);
                if (mt_rand(1, 100) < $i[2]) {
                    if (!isset($this->debug)) {
                        $list[] = new B($i[0], isset($i[3]) ? $i[3] : 0, $i[1]);
                    } else {
                        $iz = B::fromString(sprintf("%d:%d", $i[0], isset($i[3]) ? $i[3] : 0));
                        $z = $i[1];
                        while ($z--) {
                            $list[] = $iz;
                        }
                    }
                }
            }
        } else {
            $c->s($n, '1:1:1');
            $c->save();
        }
        return $list;
    }
    public function tmy(EntityExplodeEvent $e)
    {
        $ev = $e->getEntity()->getId();
        if (in_array($ev, $this->arr)) {
            unset($this->arr[$ev]);
        }
    }
    public function getc($name)
    {
        if (isset($this->arr[$name])) {
            return count($this->arr[$name]);
        } else {
            $this->arr[$name] = [];
            return 0;
        }
    }
    public function adde($a, $name)
    {
        $this->arr[$name][] = $a;
    }
    public function onEnable()
    {
        try {
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
            @mkdir($this->getDataFolder());
            $sh = $this->getServer()->getScheduler();
            $this->c = new mycfg($this->getDataFolder() . "drop.yml");
            $this->md = new mycfg($this->getDataFolder() . "cmd.yml");
            $this->gongji = new mycfg($this->getDataFolder() . "gongji.yml");
            $this->c->save();
            $this->md->save();
            $this->cf = new Config($this->getDataFolder() . "cfg.yml", Config::YAML, array());
            $this->orz = new Config($this->getDataFolder() . "map.yml", Config::YAML, array('test' => '1kill'));
            $this->orz->save();
            $this->lz = new Config($this->getDataFolder() . "adv.yml", Config::YAML, array());
            if (!$this->lz->exists('创造允许打怪')) {
                $this->lz->set('创造允许打怪', 1);
                $this->lz->set('levellock', 'null');
                $this->lz->set('aifix', 0);
                $this->lz->set('粒子开关', 1);
                $this->lz->save();
            }
            if (!$this->cf->exists("firstaa")) {
                $def = array();
                $this->cf->set("firstaa", 10);
                $def["dis"] = 4;
                $def["level"] = 1;
                $def["x"] = $def["y"] = $def["z"] = 5;
                $def["hp"] = 15;
                $def["name"] = "xxxx";
                $def["count"] = -2;
                $def["type"] = "Zombie";
                $this->cf->set("default", $def);
                $this->cf->set("points", array("default"));
                $this->cf->set('rate', 20);
                $this->cf->save();
            }
            $this->czdg = (int) $this->lz->get('创造允许打怪');
            $sh->scheduleRepeatingTask(new jb($this), $this->cf->get("rate"));
            if ($this->lz->get('aifix')) {
                $sh->scheduleRepeatingTask(new upd($this), $this->lz->get('aifix'));
            }
            if ($this->lz->get('levellock') != 'null') {
                $this->locked = $this->lz->get('levellock');
            }
            unset($data, $sh);
            $this->initi();
            $this->getServer()->setConfigInt("difficulty", 3);
        } catch (\Exception $e) {
            $ti = 5;
            while ($ti--) {
                echo 'entpvp插件配置文件加载错误！！！看看介绍的yml格式吧！' . "\n" . $e->getMessage() . "\n";
                sleep(1);
            }
            die;
        }
    }
    private function rcd($n, $c, $p)
    {
        if ($c->exists($n) == 1) {
            $cd = explode(';', $c->g($n));
            foreach ($cd as $sd) {
                if ($sd == '') {
                    continue;
                }
                if ($sd[0] == '@') {
                    $sda = $this->orz->get(substr($sd, 1));
                    $crun = $sda[0] == '1' ? $p : new \pocketmine\command\ConsoleCommandSender();
                    $sda = substr($sda, 1);
                    $sda = str_ireplace('{p}', $p->getname(), $sda);
                    if ($sda != '') {
                        $this->getServer()->dispatchCommand($crun, $sda);
                    }
                    continue;
                }
                $sd = str_ireplace('{p}', $p->getname(), $sd);
                if ($sd != '') {
                    $this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), $sd);
                }
            }
        } else {
            $c->s($n, '');
            $c->save();
        }
    }
    public function gc(EntityDeathEvent $e)
    {
        $ev = $e->getEntity()->getId();
        if (($naive = $this->getN($ev)) == -1) {
            return;
        }
        unset($this->arr[$naive][$this->ggg]);
        $e->setDrops($this->dop($naive, $this->c));
        $lt = $e->getEntity()->getLastDamageCause();
        if ($lt instanceof EntityDamageByEntityEvent) {
            if (($p = $lt->getDamager()) instanceof Player) {
                if ($p->getLevel()->getId() != $e->getEntity()->getLevel()->getId()) {
                    return 0;
                }
                $this->rcd($naive, $this->md, $p);
                $this->callapi($this->ED, array($e, $lt, $naive));
                return 0;
            }
        }
    }
}
class jb extends PluginTask
{
    public $p;
    public function __construct(kill $plugin)
    {
        parent::__construct($plugin);
        $this->p = $plugin;
    }
    public function spaw($x, $y, $z, $hp, $p, $pt, $pta, $l)
    {
        if (p5) {
            $nbt = new \pocketmine\nbt\tag\Compound();
            $nbt->Pos = new \pocketmine\nbt\tag\Enum("Pos", [new \pocketmine\nbt\tag\Double("", $x), new \pocketmine\nbt\tag\Double("", $y), new \pocketmine\nbt\tag\Double("", $z)]);
            $nbt->Rotation = new \pocketmine\nbt\tag\Enum("Rotation", [new \pocketmine\nbt\tag\Float("", 50), new \pocketmine\nbt\tag\Float("", 50)]);
        } else {
            $nbt = new \pocketmine\nbt\tag\CompoundTag();
            $nbt->Pos = new \pocketmine\nbt\tag\ListTag("Pos", [new \pocketmine\nbt\tag\DoubleTag("", $x), new \pocketmine\nbt\tag\DoubleTag("", $y), new \pocketmine\nbt\tag\DoubleTag("", $z)]);
            $nbt->Rotation = new \pocketmine\nbt\tag\ListTag("Rotation", [new \pocketmine\nbt\tag\FloatTag("", 50), new \pocketmine\nbt\tag\FloatTag("", 50)]);
        }
        $a = Entity::createEntity($pt["type"], $l, $nbt);
        if ($a == null) {
            $ti = 10;
            while ($ti--) {
                $p->getServer()->broadcastMessage('nmb刷怪出错了，检查刷怪点type!如果你是玩家请给腐竹反馈\\n刷怪点名称:' . $pta);
            }
            sleep(10);
        }
        $a->setMaxHealth($hp);
        $a->setHealth($hp);
        return $a;
    }
    public function onRun($currentTick)
    {
        $p = $this->p;
        $py = $p->getServer()->getOnlinePlayers();
        foreach ($py as $play) {
            $lid = $play->getlevel()->getid();
            $ln = $play->getlevel()->getFolderName();
            $nd = [];
            if (isset($p->cache[$ln])) {
                $nd = $p->cache[$ln];
            }
            if (isset($p->cache[$lid])) {
                foreach ($p->cache[$lid] as $k => $v) {
                    $nd[$k] = $v;
                }
            }
            if (count($nd) == 0) {
                continue;
            }
            foreach ($nd as $name => $vvv) {
                try {
                    if ($play->distance($vvv) <= $p->cc[$name]["dis"]) {
                        $pt = $p->cc[$name];
                        if ($p->getc($name) < $pt["count"] || $pt['count'] == -10000) {
                            $ent = $this->spaw($vvv->x, $vvv->y, $vvv->z, $pt["hp"], $p, $pt, $name, $play->getlevel());
                            $ent->setNameTag(str_replace('@', "\n", $pt["name"]));
                            caonima($ent);
                            $p->adde($ent->getId(), $name);
                            $ent->spawnToAll();
                        }
                    }
                } catch (\Exception $e) {
                    $i = 10;
                    while ($i--) {
                        Server::broadcastMessage("刷怪点炸了啊啊啊，让你瞎jb删配置！！！刷怪点名字:{$name}");
                    }
                }
            }
        }
        unset($nd, $ent);
    }
}
class mycfg
{
    public $cache = [], $f;
    public function __construct($f)
    {
        if (is_file($f)) {
            $this->data = file_get_contents($f);
        } else {
            file_put_contents($f, "\n");
            $this->f = $f;
            $this->data = file_get_contents($f);
        }
        $this->f = $f;
        $this->data = explode("\n", $this->data);
        $this->go();
        unset($this->data);
    }
    public function go()
    {
        foreach ($this->data as $l) {
            $l = explode('=', $l);
            if (count($l) != 2) {
                continue;
            }
            $this->cache[$l[0]] = $l[1];
        }
    }
    public function s($k, $v)
    {
        $this->cache[$k] = $v;
        $this->save();
    }
    public function g($k)
    {
        return $this->cache[$k];
    }
    public function ga()
    {
        return $this->cache;
    }
    public function sa($k)
    {
        $this->cache = $k;
    }
    public function save()
    {
        $d = "/*easy config powered by cpera*/\n";
        foreach ($this->cache as $k => $v) {
            $d .= $k . '=' . $v . "\n";
        }
        file_put_contents($this->f, $d);
        unset($d, $k, $v);
    }
    public function exists($k)
    {
        return isset($this->cache[$k]);
    }
}
class upd extends PluginTask
{
    public function onRun($ct)
    {
       // echo memory_get_usage() . "\n";
        $s = Server::getInstance();
        $ls = $s->getLevels();
        foreach ($ls as $l) {
            foreach ($l->getEntities() as $t) {
                if (method_exists($t, 'updateTick')) {
                    $t->updateTick();
                }
            }
        }
        unset($s, $ls, $l, $t, $ct);
    }
}
