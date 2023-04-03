<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\Payment\Cashbill\Channel;

/**
 * Przechowuje kolekcje kanałów płatności
 *
 * @implements \IteratorAggregate<string, Channel>
 */
class ChannelCollection implements \IteratorAggregate, \Countable
{
    /**
     * Tablica kanałów płatności
     *
     * @var array<string, Channel>
     */
    private array $channels = [];

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->channels);
    }

    /**
     * @inheritDoc
     * @return \ArrayIterator<string, Channel>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @return array<string, Channel> Zwraca tablice kanałów płatności
     */
    public function all(): array
    {
        return $this->channels;
    }

    /**
     * @param Channel $channel Kanał płatności
     * @return void
     */
    public function add(Channel $channel): void
    {
        $name = $channel->getId();
        unset($this->channels[$name]);
        $this->channels[$name] = $channel;
    }

    /**
     * Zwraca kanał płatności.
     *
     * @param string $name Identyfikator kanału płatności
     * @return Channel|null
     */
    public function get(string $name): ?Channel
    {
        return $this->channels[$name] ?? null;
    }

    /**
     * Usuwa kanał płatności.
     *
     * @param string|array $name Identyfikator kanału płatności
     * @return void
     */
    public function remove(string|array $name)
    {
        foreach ((array) $name as $n) {
            unset($this->channels[$n]);
        }
    }
}