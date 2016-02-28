<?php

namespace AppBundle\Service;

use Google_Client;
use Google_Exception;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Ndewez\WebHome\CalendarApiBundle\V0\Model\GoogleConnection;

/**
 * Class GoogleCalendarClient.
 */
class GoogleCalendarClient
{
    const CREDENTIALS_PATH = '~/.credentials/calendar-php-quickstart.json';

    /** @var Google_Client */
    private $client;

    /** @var Google_Service_Calendar */
    private $service;

    /**
     * @param GoogleConnection $connection
     * @param string           $title
     * @param bool             $allDay
     * @param \DateTime        $start
     * @param \DateTime        $end
     *
     * @return string
     */
    public function create(GoogleConnection $connection, $title, $allDay, \DateTime $start, \DateTime $end)
    {
        $this->buildClient($connection);

        list($startArray, $endArray) = $this->getEventDates($start, $end, $allDay);

        $event = new Google_Service_Calendar_Event([
            'summary' => $title,
            'start' => $startArray,
            'end' => $endArray,
        ]);

        $event = $this->service->events->insert($connection->getInternalId(), $event);

        return $event->getId();
    }

    /**
     * @param GoogleConnection $connection
     * @param string           $eventId
     * @param string           $title
     * @param bool             $allDay
     * @param \DateTime        $start
     * @param \DateTime        $end
     */
    public function update(GoogleConnection $connection, $eventId, $title, $allDay, \DateTime $start, \DateTime $end)
    {
        $this->buildClient($connection);

        list($startArray, $endArray) = $this->getEventDates($start, $end, $allDay);

        // Get event for update
        $event = $this->service->events->get($connection->getInternalId(), $eventId);

        $event->setSummary($title);
        $event->setStart(new Google_Service_Calendar_EventDateTime($startArray));
        $event->setEnd(new Google_Service_Calendar_EventDateTime($endArray));

        $this->service->events->update($connection->getInternalId(), $event->getId(), $event);
    }

    public function delete(GoogleConnection $connection, $eventId)
    {
        $this->buildClient($connection);

        $this->service->events->delete($connection->getInternalId(), $eventId);
    }

    /**
     * @param GoogleConnection $connection
     *
     * @throws Google_Exception
     */
    private function buildClient(GoogleConnection $connection)
    {
        $this->client = new Google_Client();
        $this->client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->client->setAuthConfig($this->getAuthConfig($connection));
        $this->client->setAccessType('offline');

        $credentialsPath = $this->expandHomeDirectory(self::CREDENTIALS_PATH);
        $this->client->setAccessToken($this->getAccessToken($credentialsPath));

        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $this->client->refreshToken($this->client->getRefreshToken());
            file_put_contents($credentialsPath, $this->client->getAccessToken());
        }

        $this->service = new Google_Service_Calendar($this->client);
    }

    /**
     * @param string $credentialsPath
     *
     * @return string
     */
    private function getAccessToken($credentialsPath)
    {
        // Load previously authorized credentials from a file.
        if (file_exists($credentialsPath)) {
            return file_get_contents($credentialsPath);
        }

        // Request authorization from the user.
        $authUrl = $this->client->createAuthUrl();

        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $this->client->authenticate($authCode);

        // Store the credentials to disk.
        $directory = dirname($credentialsPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0700, true);
        }

        file_put_contents($credentialsPath, $accessToken);

        printf("Credentials saved to %s\n", $credentialsPath);

        return $accessToken;
    }

    /**
     * @param GoogleConnection $connection
     *
     * @return string
     */
    private function getAuthConfig(GoogleConnection $connection)
    {
        return json_encode([
            'installed' => [
                'client_id' => $connection->getClientId(),
                'project_id' => $connection->getProjectId(),
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://accounts.google.com/o/oauth2/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_secret' => $connection->getClientSecret(),
                'redirect_uris' => [
                    'urn:ietf:wg:oauth:2.0:oob',
                    'http://localhost',
                ],
            ],
        ]);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE').getenv('HOMEPATH');
        }

        return str_replace('~', realpath($homeDirectory), $path);
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @param bool      $allDay
     *
     * @return array
     */
    private function getEventDates(\DateTime $start, \DateTime $end, $allDay)
    {
        $startArray = [
            'date' => $start->format('Y-m-d'),
            'timeZone' => $start->getTimezone(),
        ];
        if (!$allDay) {
            $startArray = [
                'date' => $start->format('c'),
                'timeZone' => $start->getTimezone(),
            ];
        }

        $endArray = [
            'date' => $end->format('Y-m-d'),
            'timeZone' => $end->getTimezone(),
        ];
        if (!$allDay) {
            $endArray = [
                'date' => $end->format('c'),
                'timeZone' => $end->getTimezone(),
            ];
        }

        return [$startArray, $endArray];
    }
}
