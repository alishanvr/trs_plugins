<?php
	
	
	class SampleTest extends PHPUnit_Framework_TestCase {
		public function testFirst() {
			$this->assertTrue(true);
		}
		
		public function testsecond() {
			$a = 10;
			$this->assertTrue($a === 150);
		}
	}