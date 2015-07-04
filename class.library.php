<?php

class DPD_Library {
    /**
     * The array of hashes and translations available to this translator.
     *
     * @var array
     */
    protected $hashes = [
        '3159615086' => 'Glimmer',
        '1415355184' => 'Crucible Marks',
        '1415355173' => 'Vanguard Marks',
        '898834093'  => 'Exo',
        '3887404748' => 'Human',
        '2803282938' => 'Awoken',
        '3111576190' => 'Male',
        '2204441813' => 'Female',
        '671679327'  => 'Hunter',
        '3655393761' => 'Titan',
        '2271682572' => 'Warlock',
        '3871980777' => 'New Monarchy',
        '529303302'  => 'Cryptarch',
        '2161005788' => 'Iron Banner',
        '452808717'  => 'Queen',
        '3233510749' => 'Vanguard',
        '1357277120' => 'Crucible',
        '2778795080' => 'Dead Orbit',
        '1424722124' => 'Future War Cult',
        '2033897742' => 'Weekly Vanguard Marks',
        '2033897755' => 'Weekly Crucible Marks',
    ];

    /**
     * Universal function for cURL'ing to Bungie API
     *
     * @param $uri
     * @return array|bool|mixed
     */
    private function callBungie($uri)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if(!$result = json_decode(curl_exec($ch), true)) {
            $result = false;
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Translate a given hash.
     *
     * @param $hash
     * @return mixed
     * @throws Exception
     */
    public function translate($hash)
    {
        if (!array_key_exists($hash, $this->hashes)) {
            throw new Exception();
        }

        return $this->hashes[$hash];
    }

    /**
     * Fetch a players membership id
     *
     * @param $username
     * @return mixed
     */
    protected function fetchPlayer($username, $platform)
    {
        $result = false;
        $uri = 'http://www.bungie.net/Platform/Destiny/SearchDestinyPlayer/'.$platform.'/'.$username;
        $json = $this->callBungie($uri);

        if (isset($json['Response'][0]['membershipId'])) {
            $result = array(
                'membershipId' => $json['Response'][0]['membershipId'],
                'membershipType' => $platform
            );
        }
        return $result;
    }

    /**
     * Fetch grimoire card count stats and format the fields ready for the front end
     *
     * @param $platform
     * @param $membershipId
     * @return array
     */
    public function fetchGrimoireForWidget($platform, $membershipId)
    {
        $result = false;
        $uri = 'http://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/'.$platform.'/'.$membershipId;

        if( $stats = $this->callBungie($uri) ) {
            $result = array(
                'grimoire_cards_acquired' => isset($stats['Response']['data']['cardCollection']) ? count($stats['Response']['data']['cardCollection']) : '??'
            );
        }

        return $result;
    }

    /**
     * Fetch player stats and format the fields ready for the front end
     *
     * @param $platform
     * @param $membershipId
     * @param $characterId
     * @return array
     */
    public function fetchHistoricalStatsForWidget($platform, $membershipId, $characterId)
    {
        $response = false;
        $uri = 'http://www.bungie.net/Platform/Destiny/Stats/'.$platform.'/'.$membershipId.'/'.$characterId;

        if( $stats = $this->callBungie($uri) ) {
            $response = array(
                'story' => array(
                    'kills' => isset($stats['Response']['story']['allTime']['kills']) ? $stats['Response']['story']['allTime']['kills']['basic']['value'] : '??',
                    'precision_kills' => isset($stats['Response']['story']['allTime']['precisionKills']) ? $stats['Response']['story']['allTime']['precisionKills']['basic']['value'] : '??',
                    'ability_kills' => isset($stats['Response']['story']['allTime']['abilityKills']) ? $stats['Response']['story']['allTime']['abilityKills']['basic']['value'] : '??',
                    'kill_death' => isset($stats['Response']['story']['allTime']['killsDeathsRatio']) ? round($stats['Response']['story']['allTime']['killsDeathsRatio']['basic']['value'], 2) : '??',

                ),
                'crucible' => array(
                    'wins' => isset($stats['Response']['allPvP']['allTime']['activitiesWon']) ? $stats['Response']['allPvP']['allTime']['activitiesWon']['basic']['value'] : '??',
                    'kills' => isset($stats['Response']['allPvP']['allTime']['kills']) ? $stats['Response']['allPvP']['allTime']['kills']['basic']['value'] : '??',
                    'precision_kills' => isset($stats['Response']['allPvP']['allTime']['precisionKills']) ? $stats['Response']['allPvP']['allTime']['precisionKills']['basic']['value'] : '??',
                    'ability_kills' => isset($stats['Response']['allPvP']['allTime']['abilityKills']) ? $stats['Response']['allPvP']['allTime']['abilityKills']['basic']['value'] : '??',
                    'kill_death' => isset($stats['Response']['allPvP']['allTime']['killsDeathsRatio']) ? round($stats['Response']['allPvP']['allTime']['killsDeathsRatio']['basic']['value'], 2) : '??',
                )
            );
        }
        return $response;
    }

    /**
     * Fetch a players membership id
     *
     * @param $username
     * @param $platform
     * @return bool
     */
    public function fetchMembershipId($username ,$platform)
    {
        if($player = $this->fetchPlayer($username, $platform)) {
            if (isset($player['membershipId'])) {
                return $player['membershipId'];
            }
        }
        return false;
    }

    /**
     * Fetch a character by username, character id and platform
     *
     * @param $username
     * @param $character_id
     * @param $platform
     * @return bool
     */
    public function fetchCharacter($username, $character_id, $platform)
    {
        if($characters = $this->fetchCharacters($username, $platform)) {
            foreach ($characters as $character) {
                if($character['characterBase']['characterId']==$character_id) {
                    return $character;
                }
            }
        }
        return false;
    }

    /**
     * Fetch a users Destiny characters.
     *
     * @param $username
     * @return mixed
     */
    public function fetchCharacters($username, $platform)
    {
        $result = array();

        if($player = $this->fetchPlayer($username, $platform)) {
            $uri = 'http://bungie.net/Platform/Destiny/'.$player['membershipType'].'/Account/'.$player['membershipId'].'?ignorecase=true';

            if( $json = $this->callBungie($uri) ) {
                foreach ($json['Response']['data']['characters'] as $character) {
                    $result[] = $character;
                }
            }
        }
        return $result;
    }

    public function fetchCharacterDescriptions($username, $network_id)
    {
        $character_descriptions = array();
        if($characters = $this->fetchCharacters($username, $network_id)) {
            foreach ($characters as $character) {
                $race = $this->translate($character['characterBase']['raceHash']);
                $gender = $this->translate($character['characterBase']['genderHash']);
                $class = $this->translate($character['characterBase']['classHash']);
                $level = $character['characterLevel'];
                $character_desc = $race . ' ' . $gender . ' ' . $class . ' (' . $level . ')';
                $character_id = $character['characterBase']['characterId'];
                $character_descriptions[] = array(
                    'character_id' => $character_id,
                    'description' => $character_desc
                );
            }

            return $character_descriptions;
        }
        return false;
    }

    /**
     * Fetch a character and format the fields ready for the front end
     *
     * @param $username
     * @param $character_id
     * @param $platform
     * @return array
     * @throws Exception
     */
    public function fetchCharacterForWidget($username, $character_id, $platform) {
        if($character = $this->fetchCharacter($username, $character_id, $platform)) {
            return array(
                'details' => array(
                    'race' => $this->translate($character['characterBase']['raceHash']),
                    'gender' => $this->translate($character['characterBase']['genderHash']),
                    'class' => $this->translate($character['characterBase']['classHash']),
                    'level' => '('.$character['characterLevel'].')',
                ),
                'emblem_path' => 'http://www.bungie.net'.$character['emblemPath'],
                'background_path' => 'http://www.bungie.net'.$character['backgroundPath']
            );
        }
        return false;
    }
}