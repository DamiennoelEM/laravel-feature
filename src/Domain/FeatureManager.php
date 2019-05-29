<?php

namespace LaravelFeature\Domain;

use LaravelFeature\Domain\Exception\FeatureException;
use LaravelFeature\Domain\Model\Feature;
use LaravelFeature\Domain\Repository\FeatureRepositoryInterface;
use LaravelFeature\Featurable\FeaturableInterface;

class FeatureManager
{
    /** @var FeatureRepositoryInterface */
    private $repository;

    /**
     * FeatureManager constructor.
     * @param FeatureRepositoryInterface $repository
     */
    public function __construct(FeatureRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function add($featureName, $isEnabled)
    {
        $feature = Feature::fromNameAndStatus($featureName, $isEnabled);
        $this->repository->save($feature);
    }

    public function remove($featureName)
    {
        $feature = $this->repository->findByName($featureName);
        $this->repository->remove($feature);
    }

    public function rename($featureOldName, $featureNewName)
    {
        $feature = $this->repository->findByName($featureOldName);
        $feature->setNewName($featureNewName);

        $this->repository->save($feature);
    }

    public function enable($featureName)
    {
        $feature = $this->repository->findByName($featureName);

        $feature->enable();

        $this->repository->save($feature);
    }

    public function disable($featureName)
    {
        $feature = $this->repository->findByName($featureName);

        $feature->disable();

        $this->repository->save($feature);
    }

    public function isEnabled($featureName)
    {
        try {
            $feature = $this->repository->findByName($featureName);
            return $feature->isEnabled();
        }
        catch (FeatureException $e) {
            return false;
        }
    }

    public function enableFor($featureName, FeaturableInterface $featurable)
    {
        $this->repository->enableFor($featureName, $featurable);
    }

    public function disableFor($featureName, FeaturableInterface $featurable)
    {
        $this->repository->disableFor($featureName, $featurable);
    }

    public function isEnabledFor($featureName, FeaturableInterface $featurable)
    {
        return $this->repository->isEnabledFor($featureName, $featurable);
    }
}
