<?php
namespace rockunit;

use rock\sanitize\Sanitize;

class IpTest extends \PHPUnit_Framework_TestCase
{
    public function test_()
    {
        $this->assertSame('2001:db8:abc:1400::-2001:db8:abc:17ff:ffff:ffff:ffff:ffff', Sanitize::ip()->sanitize('2001:db8:abc:1400::/54'));
        $this->assertSame('2001:db8:abc:1400::-2001:db8:abc:17ff:ffff:ffff:ffff:ffff', Sanitize::ip()->sanitize('2001:db8:abc:1400::/FFFF:FFFF:FFFF:FC00:0000:0000:0000:0000'));
        $this->assertSame('73.35.143.32-73.35.143.63', Sanitize::ip()->sanitize('73.35.143.32/27'));
        $this->assertSame('73.35.143.32-73.35.143.63', Sanitize::ip()->sanitize('73.35.143.32/255.255.255.224'));
        $this->assertSame('192.0.0.0-192.255.255.255', Sanitize::ip()->sanitize('192.*.*.*'));
        $this->assertSame('0.0.0.0-255.255.255.255', Sanitize::ip()->sanitize('*.*.*.*'));
        $this->assertSame('192.0.2.6-192.255.2.6', Sanitize::ip()->sanitize('192.*.2.6'));
        $this->assertSame('2001:cdba::-2001:cdba::ffff:ffff', Sanitize::ip()->sanitize('2001:cdba:0000:0000:0000:0000:*:*'));
        $this->assertSame('2001:cdba::1234:3214-2001:cdba:0:ffff::1234:3214', Sanitize::ip()->sanitize('2001:cdba:0000:*:0000:0000:1234:3214'));
        $this->assertSame('127.0.0.1', Sanitize::ip()->sanitize('127.0.0.1'));
    }
}
