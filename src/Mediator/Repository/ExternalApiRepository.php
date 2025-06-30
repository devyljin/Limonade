<?php

namespace Agrume\Limonade\Mediator\Repository;

use TypeError;

class ExternalApiRepository
{
    public const EXACT_MODE = 0;
    public const LEVENSTEIN_MODE = 1;
    public const PERCENTAGE_MODE = 2;

    private const DEFAULT_PERCENTAGE_MODE_WEIGHT = 50;
    private const DEFAULT_LEVENSTEIN_MODE_WEIGHT = 2;

    protected array $data;
    public function __construct(array $data)
    {
        if(is_array($data)) {
            $this->data = $data;
        } else {
            throw new TypeError("Data must be an array to build a repository");
        }
    }
    /**
     * Get all records from the repository.
     *
     * @return array
     */
    public function findAll():array
    {
        return $this->data;
    }
    /**
     * Find records by ID or a specified identifier.
     *
     * @param mixed $id The ID value to search for
     * @param string $identifier The key name to match the ID against (default: "id")
     * @return array
     */
    public function find($id, $identifier = "id"): array
    {
        return $this->findBy([$identifier => $id]);
    }
    /**
     * Find records matching given criteria. Criteria supports multiple match modes:
     * - Exact match: ['name' => 'John']
     * - Mode-based match: ['name' => ['John', ExternalApiRepository::LEVENSTEIN_MODE, 3]]
     *
     * @param array $criteria Associative array of keys and values or value/mode/weight triplets
     * @return array Filtered results
     */
    public function findBy($criteria): array
    {

        $filter = array_filter($this->data,function($v) use ($criteria) {
            $sim = [];
            foreach ($criteria as $key => $value) {
                if(isset($v[$key])){
                    if(is_array($value)){
                        $data = $value["value"] ?? $value[0];
                        $mode = $value["mode"] ?? $value[1] ??  self::EXACT_MODE;
                        $weight = $value["weight"] ?? $value[2] ??null;
                        if($mode === self::LEVENSTEIN_MODE) {
                            if($this->filterByLevenstein($v[$key] ,$data, $weight)){
                                $sim[] = true;
                            }
                        }
                        if($mode === self::PERCENTAGE_MODE) {
                            if($this->filterByPercentage($v[$key] ,$data, $weight)){
                                $sim[] = true;
                            }
                        }
                        if($mode === self::EXACT_MODE) {
                            if($v[$key] === $data){
                                $sim[] = true;
                            }
                        }
                    } else if($v[$key] === $value) {
                        $sim[] = true;
                    }
                }

            }
            return (count($sim) === count($criteria));
        });

        return  array_values($filter);
    }
    /**
     * Levenshtein filter helper. /!\ Use it with caution /!\
     *
     * @param string $repo Value from the repository
     * @param string $value Value to match against
     * @param int|null $weight Maximum distance allowed (default: 2)
     * @return bool True if match passes
     */
    private function filterByLevenstein($repo, $value, $weight = self::DEFAULT_LEVENSTEIN_MODE_WEIGHT):bool
    {
        if(is_null($weight)){
            $weight = self::DEFAULT_LEVENSTEIN_MODE_WEIGHT;
        }
        return (levenshtein($repo, $value) <= $weight);
    }
    /**
     * Percentage similarity filter helper. /!\ Use it with caution /!\
     *
     * @param string $repo Value from the repository
     * @param string $value Value to match against
     * @param int|null $weight Minimum similarity percentage required (default: 50)
     * @return bool True if match passes
     */
    private function filterByPercentage($repo, $value, $weight = self::DEFAULT_PERCENTAGE_MODE_WEIGHT):bool
    {
        if(is_null($weight)){
            $weight = self::DEFAULT_PERCENTAGE_MODE_WEIGHT;
        }
        similar_text($repo, $value, $percent);
        return ($percent >= $weight);
    }

}