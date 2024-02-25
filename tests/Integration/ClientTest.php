<?php

namespace JoemagsApps\WACloudApi\Tests\Integration;

use JoemagsApps\WACloudApi\Client;
use JoemagsApps\WACloudApi\Message\TextMessage;
use JoemagsApps\WACloudApi\Request;
use JoemagsApps\WACloudApi\Tests\WACloudApiTestConfiguration;
use JoemagsApps\WACloudApi\WACloudApi;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
final class ClientTest extends TestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = new Client(WACloudApi::DEFAULT_GRAPH_VERSION);
    }

    public function test_send_text_message()
    {
        $message = new TextMessage(
            WACloudApiTestConfiguration::$to_phone_number_id,
            'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es',
            true
        );
        $request = new Request\MessageRequest\RequestTextMessage(
            $message,
            WACloudApiTestConfiguration::$access_token,
            WACloudApiTestConfiguration::$from_phone_number_id
        );

        $response = $this->client->sendMessage($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
    }

    public function test_upload_media()
    {
        $request = new Request\MediaRequest\UploadMediaRequest(
            'tests/Support/netflie.png',
            WACloudApiTestConfiguration::$from_phone_number_id,
            WACloudApiTestConfiguration::$access_token
        );

        $response = $this->client->uploadMedia($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
        $this->assertArrayHasKey('id', $response->decodedBody());

        return $response->decodedBody()['id'];
    }

    /**
     * @depends test_upload_media
     */
    public function test_download_media(string $media_id)
    {
        $request = new Request\MediaRequest\DownloadMediaRequest(
            $media_id,
            WACloudApiTestConfiguration::$access_token
        );

        $response = $this->client->downloadMedia($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
    }

    public function test_business_profile()
    {
        $request = new Request\BusinessProfileRequest\BusinessProfileRequest(
            'about',
            WACloudApiTestConfiguration::$access_token,
            WACloudApiTestConfiguration::$from_phone_number_id
        );

        $response = $this->client->businessProfile($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
    }

    public function test_update_business_profile()
    {
        $request = new Request\BusinessProfileRequest\UpdateBusinessProfileRequest(
            [
                'about' => 'About text',
                'email' => 'my-email@email.com'
            ],
            WACloudApiTestConfiguration::$access_token,
            WACloudApiTestConfiguration::$from_phone_number_id
        );

        $response = $this->client->updateBusinessProfile($request);

        $this->assertEquals(200, $response->httpStatusCode());
        $this->assertEquals($request, $response->request());
        $this->assertEquals(false, $response->isError());
    }
}
