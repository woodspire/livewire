<?php

namespace Livewire\Features\SupportBladeAttributes;

use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\Livewire;

class Test extends \Tests\TestCase
{
    /** @test */
    public function it_adds_wire_macro_to_component_attribute_bag()
    {
        $bag = new ComponentAttributeBag([
            'wire:model.defer' => 'foo',
            'wire:click.debounce.100ms' => 'bar',
            'wire:keydown.enter' => 'baz',
        ]);

        $this->assertEquals('foo', $bag->wire('model'));
        $this->assertEquals(['defer'], $bag->wire('model')->modifiers()->toArray());
        $this->assertTrue($bag->wire('model')->hasModifier('defer'));
        $this->assertEquals('wire:model.defer="foo"', $bag->wire('model')->toHtml());

        $this->assertEquals('bar', $bag->wire('click'));
        $this->assertEquals(['debounce', '100ms'], $bag->wire('click')->modifiers()->toArray());

        $this->assertEquals('baz', $bag->wire('keydown'));
        $this->assertEquals(['enter'], $bag->wire('keydown')->modifiers()->toArray());
    }

    /** @test */
    public function entangle_directive_adds_dot_defer_if_defer_modifier_is_present()
    {
        // @todo: Should this be in support entangle feature?
        $dom = Livewire::test(ComponentWithEntangleDirectiveUsedWithinBladeComponent::class)
            ->html();

        $this->assertStringContainsString("{ foo: window.Livewire.find('", $dom);
        $this->assertStringContainsString("').entangle('foo') }", $dom);
        $this->assertStringContainsString("').entangle('bar').live }", $dom);
    }
}

class ComponentWithEntangleDirectiveUsedWithinBladeComponent extends Component
{
    public function render()
    {
        return <<<'HTML'
        <x-input wire:model="foo"/>

        <x-input wire:model.live="bar"/>
        HTML;
    }
}
