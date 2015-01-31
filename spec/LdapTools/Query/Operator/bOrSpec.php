<?php
/**
 * This file is part of the LdapTools package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\LdapTools\Query\Operator;

use LdapTools\Query\Operator\bAnd;
use LdapTools\Query\Operator\Comparison;
use LdapTools\Query\Operator\Wildcard;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class bOrSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Comparison('foo', Comparison::EQ, 'bar'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('LdapTools\Query\Operator\bOr');
    }

    function it_should_implement_ContainsOperatorsInferface()
    {
        $this->shouldImplement('\LdapTools\Query\Operator\ContainsOperatorsInterface');
    }

    function it_should_have_a_pipe_symbol()
    {
        $this->getOperatorSymbol()->shouldBeEqualTo('|');
    }

    function it_should_return_one_child_when_calling_getChildren()
    {
        $this->getChildren()->shouldHaveCount(1);
    }

    function it_should_return_correct_child_count_after_adding_operators_to_it()
    {
        $this->add(new bAnd());
        $this->getChildren()->shouldHaveCount(2);
    }

    function it_should_return_the_correct_ldap_filter_with_one_operator()
    {
        $this->__tostring()->shouldBeEqualTo('(|(foo=\62\61\72))');
    }

    function it_should_return_the_correct_ldap_filter_with_two_operators()
    {
        $this->add(new Wildcard('foobar', Wildcard::ENDS_WITH,'bar'));
        $this->__tostring()->shouldBeEqualTo('(|(foo=\62\61\72)(foobar=*\62\61\72))');
    }

    function it_should_return_the_correct_ldap_filter_when_nesting_operators()
    {
        $this->add(new bAnd(new Wildcard('description', Wildcard::CONTAINS,'bar')));
        $this->__tostring()->shouldBeEqualTo('(|(foo=\62\61\72)(&(description=*\62\61\72*)))');
    }
}
