<?php

namespace Sabre\DAV;

use Sabre\HTTP;

require_once 'Sabre/HTTP/ResponseMock.php';
require_once 'Sabre/DAV/AbstractServer.php';

class ServerFinderBlockTest extends AbstractServer{

    function testPut() {

        $serverVars = array(
            'REQUEST_URI'    => '/testput.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_X_EXPECTED_ENTITY_LENGTH' => '20',
        );

        $request = HTTP\Sapi::createFromServerArray($serverVars);
        $request->setBody('Testing finder');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals(201, $this->response->status);
        $this->assertEquals('0', $this->response->headers['Content-Length']);

        $this->assertEquals('Testing finder',file_get_contents(SABRE_TEMPDIR . '/testput.txt'));

    }

    function testPutFail() {

        $serverVars = array(
            'REQUEST_URI'    => '/testput.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_X_EXPECTED_ENTITY_LENGTH' => '20',
        );

        $request = HTTP\Sapi::createFromServerArray($serverVars);
        $request->setBody('');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals(403, $this->response->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ),$this->response->headers);

        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/testput.txt'));
    }
}
