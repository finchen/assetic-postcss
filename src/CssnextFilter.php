<?php

/*
 * This file is part of the assetic autoprefixer filter package.
 *
 * (c) 2014 Tristan Lins
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bit3\Assetic\Filter\Postcss;

use Assetic\Asset\AssetInterface;
use Assetic\Exception\FilterException;
use Assetic\Filter\BaseNodeFilter;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class CssnextFilter extends BaseNodeFilter
{
    /**
     * The path to the autoprefixer binary.
     *
     * @var string
     */
    protected $moduleBin;

    /**
     * The path to the node.js binary.
     *
     * @var string|null
     */
    protected $nodeBin;

    /**
     * Option(s) concatened after the $moduleBin
     * This is very (too) permissive. We will review once we know more about postCss module.
     *
     * @var string
     */
    protected $moduleOptions;

    public function __construct($moduleBin = '/usr/bin/cssnext', $nodeBin = null, $options = '')
    {
        $this->moduleBin = $moduleBin;
        $this->nodeBin = $nodeBin;
        $this->moduleOptions = $options;
    }

    /**
     * Get the path to the postcss module binary.
     *
     * @return string
     */
    public function getModuleBin()
    {
        return $this->moduleBin;
    }

    /**
     * Set the path to the postcss module binary.
     *
     * @param string $moduleBin
     *
     * @return static
     */
    public function setModuleBin($moduleBin)
    {
        $this->moduleBin = (string) $moduleBin;

        return $this;
    }

    /**
     * Get the path to the node.js binary.
     *
     * @return string
     */
    public function getNodeBin()
    {
        return $this->nodeBin;
    }

    /**
     * Set the path to the node.js binary.
     *
     * @param string $nodeBin
     *
     * @return static
     */
    public function setNodeBin($nodeBin)
    {
        $this->nodeBin = empty($nodeBin) ? null : (string) $nodeBin;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterLoad(AssetInterface $asset)
    {
        $input = tempnam(sys_get_temp_dir(), 'assetic_postcss');
        file_put_contents($input, $asset->getContent());

        $processBuilder = $this->createProcessBuilder(array($this->moduleBin));

        if ($this->nodeBin) {
            $processBuilder->setPrefix($this->nodeBin);
        }

        // options (eg. cssnext --compress --verbose)
        $processBuilder->add($this->moduleOptions);

        // input file
        $processBuilder->add($input);

        try {
            $process = $processBuilder->getProcess();
            $process->run();
            unlink($input);

            if (!$process->isSuccessful()) {
                throw FilterException::fromProcess($process)->setInput($asset->getContent());
            }
        } catch (ProcessFailedException $exception) {
            unlink($input);
            throw $exception;
        } catch (ProcessTimedOutException $exception) {
            unlink($input);
            throw $exception;
        }

        $asset->setContent($process->getOutput());
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function filterDump(AssetInterface $asset)
    {
    }
}
