<?php

/*
 * (c) Packagist Conductors UG (haftungsbeschränkt) <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient\Api;

use PrivatePackagist\ApiClient\Api\Customers\MagentoLegacyKeys;
use PrivatePackagist\ApiClient\Exception\InvalidArgumentException;

class Customers extends AbstractApi
{
    public function all()
    {
        return $this->get('/customers/');
    }

    public function show($customerIdOrUrlName)
    {
        return $this->get(sprintf('/customers/%s/', $customerIdOrUrlName));
    }

    public function create($name, $accessToVersionControlSource = false, $urlName = null)
    {
        $parameters = [
            'name' => $name,
            'accessToVersionControlSource' => $accessToVersionControlSource,
        ];
        if ($urlName) {
            $parameters['urlName'] = $urlName;
        }

        return $this->post('/customers/', $parameters);
    }

    /**
     * @deprecated Use edit instead
     */
    public function update($customerIdOrUrlName, array $customer)
    {
        return $this->edit($customerIdOrUrlName, $customer);
    }

    public function edit($customerIdOrUrlName, array $customer)
    {
        return $this->put(sprintf('/customers/%s/', $customerIdOrUrlName), $customer);
    }

    public function remove($customerIdOrUrlName)
    {
        return $this->delete(sprintf('/customers/%s/', $customerIdOrUrlName));
    }

    public function listPackages($customerIdOrUrlName)
    {
        return $this->get(sprintf('/customers/%s/packages/', $customerIdOrUrlName));
    }

    /**
     * @deprecated Use addOrEditPackages instead
     */
    public function addOrUpdatePackages($customerIdOrUrlName, array $packages)
    {
        return $this->addOrEditPackages($customerIdOrUrlName, $packages);
    }

    public function addOrEditPackages($customerIdOrUrlName, array $packages)
    {
        foreach ($packages as $package) {
            if (!isset($package['name'])) {
                throw new InvalidArgumentException('Parameter "name" is required.');
            }
        }

        return $this->post(sprintf('/customers/%s/packages/', $customerIdOrUrlName), $packages);
    }

    /**
     * @deprecated Use addOrEditPackages instead
     */
    public function addPackages($customerIdOrUrlName, array $packages)
    {
        return $this->addOrEditPackages($customerIdOrUrlName, $packages);
    }

    public function removePackage($customerIdOrUrlName, $packageName)
    {
        return $this->delete(sprintf('/customers/%s/packages/%s/', $customerIdOrUrlName, $packageName));
    }

    public function regenerateToken($customerIdOrUrlName, array $confirmation)
    {
        if (!isset($confirmation['IConfirmOldTokenWillStopWorkingImmediately'])) {
            throw new InvalidArgumentException('Confirmation is required to regenerate the Composer repository token.');
        }

        return $this->post(sprintf('/customers/%s/token/regenerate', $customerIdOrUrlName), $confirmation);
    }

    public function magentoLegacyKeys()
    {
        return new MagentoLegacyKeys($this->client);
    }
}
