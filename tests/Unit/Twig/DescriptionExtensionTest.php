<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\Twig;

use Symfony\Cmf\Bundle\ResourceBundle\Twig\DescriptionExtension;
use Symfony\Cmf\Component\Resource\Description\Description;
use Symfony\Cmf\Component\Resource\Description\DescriptionFactory;
use Symfony\Cmf\Component\Resource\Repository\Resource\CmfResource;

class DescriptionExtensionTest extends \PHPUnit\Framework\TestCase
{
    private $descriptionFactory;

    private $twig;

    public function setUp()
    {
        $this->descriptionFactory = $this->prophesize(DescriptionFactory::class);
        $extension = new DescriptionExtension(
            $this->descriptionFactory->reveal()
        );
        $this->twig = new \Twig_Environment(new \Twig_Loader_Array([]));
        $this->twig->addExtension($extension);
        $this->resource = $this->prophesize(CmfResource::class);
        $this->description = $this->prophesize(Description::class);
    }

    /**
     * It should provide the cmf_resource_description function.
     */
    public function testProvideResourceDescription()
    {
        $this->descriptionFactory->getPayloadDescriptionFor($this->resource->reveal())->willReturn($this->description->reveal());
        $this->description->get('hello')->willReturn('World');

        $template = $this->twig->createTemplate('{{ cmf_resource_description(resource).get("hello") }}');
        $result = $template->render([
            'resource' => $this->resource->reveal(),
        ]);

        $this->assertEquals('World', $result);
    }
}
