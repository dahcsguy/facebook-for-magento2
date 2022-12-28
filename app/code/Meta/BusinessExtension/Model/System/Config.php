<?php
/**
 * Copyright (c) Meta Platforms, Inc. and affiliates.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Meta\BusinessExtension\Model\System;

use Magento\Config\Model\ResourceModel\Config as ResourceConfig;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    const MODULE_NAME = 'Meta_BusinessExtension';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACTIVE = 'facebook/business_extension/active';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_INSTALLED = 'facebook/business_extension/installed';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_EXTERNAL_BUSINESS_ID = 'facebook/business_extension/external_business_id';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PIXEL_ID = 'facebook/business_extension/pixel_id';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PIXEL_AAM_SETTINGS = 'facebook/business_extension/pixel_aam_settings';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PROFILES = 'facebook/business_extension/profiles';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PAGE_ID = 'facebook/business_extension/page_id';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_CATALOG_ID = 'facebook/business_extension/catalog_id';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_COMMERCE_ACCOUNT_ID = 'facebook/business_extension/commerce_account_id';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_FEED_ID = 'facebook/business_extension/feed_id';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_OFFERS_FEED_ID = 'facebook/business_extension/offers_feed_id';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_STORE = 'facebook/business_extension/store';

    const XML_PATH_FACEBOOK_DAILY_PRODUCT_FEED = 'facebook/catalog_management/daily_product_feed';
    const XML_PATH_FACEBOOK_FEED_UPLOAD_METHOD = 'facebook/catalog_management/feed_upload_method';
    const XML_PATH_FACEBOOK_PRODUCT_IDENTIFIER = 'facebook/catalog_management/product_identifier';
    const XML_PATH_FACEBOOK_PRICE_INCL_TAX = 'facebook/catalog_management/price_incl_tax';
    const XML_PATH_FACEBOOK_COLLECTIONS_SYNC_IS_ACTIVE = 'facebook/catalog_management/collections_sync';

    const XML_PATH_FACEBOOK_SHIPPING_METHODS_STANDARD = 'facebook/shipping_methods/standard';
    const XML_PATH_FACEBOOK_SHIPPING_METHODS_EXPEDITED = 'facebook/shipping_methods/expedited';
    const XML_PATH_FACEBOOK_SHIPPING_METHODS_RUSH = 'facebook/shipping_methods/rush';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACCESS_TOKEN = 'facebook/business_extension/access_token';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PAGE_ACCESS_TOKEN = 'facebook/business_extension/page_access_token';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_INCREMENTAL_PRODUCT_UPDATES = 'facebook/catalog_management/incremental_product_updates';

    const XML_PATH_FACEBOOK_ENABLE_INVENTORY_UPLOAD = 'facebook/inventory_management/enable_inventory_upload';
    const XML_PATH_FACEBOOK_USE_MULTI_SOURCE_INVENTORY = 'facebook/inventory_management/use_multi_source_inventory';
    const XML_PATH_FACEBOOK_INVENTORY_STOCK = 'facebook/inventory_management/inventory_stock';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_OUT_OF_STOCK_THRESHOLD = 'facebook/inventory_management/out_of_stock_threshold';

    const XML_PATH_FACEBOOK_ORDERS_SYNC_ACTIVE = 'facebook/orders_sync/active';
    const XML_PATH_FACEBOOK_ORDERS_SYNC_DEFAULT_ORDER_STATUS = 'facebook/orders_sync/default_order_status';
    const XML_PATH_FACEBOOK_AUTO_SUBSCRIBE_TO_NEWSLETTER = 'facebook/orders_sync/auto_subscribe_to_newsletter';
    const XML_PATH_FACEBOOK_ORDER_SHIP_EVENT = 'facebook/orders_sync/order_ship_event';

    const XML_PATH_FACEBOOK_USE_DEFAULT_FULFILLMENT_LOCATION = 'facebook/orders_sync/default_fulfillment_location';
    const XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_STREET_LINE_1 = 'facebook/orders_sync/street_line1';
    const XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_STREET_LINE_2 = 'facebook/orders_sync/street_line2';
    const XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_COUNTRY_ID = 'facebook/orders_sync/country_id';
    const XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_STATE = 'facebook/orders_sync/region_id';
    const XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_CITY = 'facebook/orders_sync/city';
    const XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_ZIP_CODE = 'facebook/orders_sync/postcode';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_DEBUG_MODE = 'facebook/debug/debug_mode';

    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_API_VERSION = 'facebook/api/version';
    const XML_PATH_FACEBOOK_BUSINESS_EXTENSION_API_VERSION_LAST_UPDATE = 'facebook/api/version_last_update';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ResourceConfig
     */
    private $resourceConfig;

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @method __construct
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceConfig $resourceConfig
     * @param ModuleListInterface $moduleList
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface  $scopeConfig,
        ResourceConfig        $resourceConfig,
        ModuleListInterface   $moduleList,
        TypeListInterface     $cacheTypeList
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConfig = $resourceConfig;
        $this->moduleList = $moduleList;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @todo Add app ID once scaled onboarding is implemented https://fburl.com/ns10a8kb
     * @return string
     */
    public function getAppId()
    {
        return '';
    }

    /**
     * @return mixed
     */
    public function getModuleVersion()
    {
        return $this->moduleList->getOne(self::MODULE_NAME)['setup_version'];
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getCommerceManagerUrl($storeId = null)
    {
        return sprintf('https://www.facebook.com/commerce/%s', $this->getCommerceAccountId($storeId));
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getCatalogManagerUrl($storeId = null)
    {
        return sprintf('https://www.facebook.com/products/catalogs/%s/products', $this->getCatalogId($storeId));
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getSupportUrl($storeId = null)
    {
        return sprintf('https://www.facebook.com/commerce/%s/support/', $this->getCommerceAccountId($storeId));
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getPromotionsUrl($storeId = null)
    {
        return sprintf('https://www.facebook.com/commerce/%s/promotions/discounts/', $this->getCommerceAccountId($storeId));
    }

    /**
     * @method isSingleStoreMode
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->storeManager->isSingleStoreMode();
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return bool
     */
    public function isActiveExtension($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACTIVE, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return bool
     */
    public function isFBEInstalled($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_INSTALLED, $scopeId, $scope);
    }

    /**
     * @return StoreManagerInterface
     */
    public function getStoreManager()
    {
        return $this->storeManager;
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        $storeId = $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_STORE);
        return $storeId >= 0 ? $storeId : $this->storeManager->getStore()->getId();
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function isActiveIncrementalProductUpdates($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_INCREMENTAL_PRODUCT_UPDATES, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function isActiveInventoryUpload($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_ENABLE_INVENTORY_UPLOAD, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function useMultiSourceInventory($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_USE_MULTI_SOURCE_INVENTORY, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getInventoryStock($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_INVENTORY_STOCK, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getOutOfStockThreshold($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_OUT_OF_STOCK_THRESHOLD, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function isActiveOrderSync($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_ORDERS_SYNC_ACTIVE, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function getDefaultOrderStatus($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_ORDERS_SYNC_DEFAULT_ORDER_STATUS, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function shouldUseDefaultFulfillmentAddress($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_USE_DEFAULT_FULFILLMENT_LOCATION, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getFulfillmentAddress($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        $address = [];

        $address['street_1'] = $this->getConfig(self::XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_STREET_LINE_1, $scopeId, $scope);
        $address['street_2'] = $this->getConfig(self::XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_STREET_LINE_2, $scopeId, $scope);
        $address['country'] = $this->getConfig(self::XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_COUNTRY_ID, $scopeId, $scope);
        $address['state'] = $this->getConfig(self::XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_STATE, $scopeId, $scope);
        $address['city'] = $this->getConfig(self::XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_CITY, $scopeId, $scope);
        $address['postal_code'] = $this->getConfig(self::XML_PATH_FACEBOOK_FULFILLMENT_LOCATION_ZIP_CODE, $scopeId, $scope);

        return $address;
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function isAutoNewsletterSubscriptionOn($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_AUTO_SUBSCRIBE_TO_NEWSLETTER, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function getOrderShipEvent($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_ORDER_SHIP_EVENT, $scopeId, $scope);
    }

    /**
     * @param $configPath
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     * @todo implement method for getting boolean values
     */
    public function getConfig($configPath, $scopeId = null, $scope = null)
    {
        if (!$scope && $this->isSingleStoreMode()) {
            return $this->scopeConfig->getValue($configPath);
        }
        try {
            $value = $this->scopeConfig->getValue($configPath, $scope ?: ScopeInterface::SCOPE_STORE, is_null($scopeId)
                ? $this->storeManager->getStore()->getId() : $scopeId);
        } catch (NoSuchEntityException $e) {
            return null;
        }
        return $value;
    }

    /**
     * @param $path
     * @param $value
     * @param null $storeId
     * @return $this
     */
    public function saveConfig($path, $value, $storeId = null)
    {
        if ($storeId) {
            $this->resourceConfig->saveConfig($path, $value, ScopeInterface::SCOPE_STORES, $storeId);
        } else {
            $this->resourceConfig->saveConfig($path, $value);
        }
        return $this;
    }

    /**
     * @param $path
     * @param null $storeId
     * @return $this
     */
    public function deleteConfig($path, $storeId = null)
    {
        if ($storeId) {
            $this->resourceConfig->deleteConfig($path, ScopeInterface::SCOPE_STORES, $storeId);
        } else {
            $this->resourceConfig->deleteConfig($path);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function cleanCache()
    {
        $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        return $this;
    }

    /**
     * @return $this
     */
    public function disableExtensionForNonDefaultStores()
    {
        $storeManager = $this->getStoreManager();
        if (!$storeManager->isSingleStoreMode()) {
            $defaultStoreId = $storeManager->getDefaultStoreView()->getId();
            foreach ($storeManager->getStores() as $store) {
                if ($store->getId() !== $defaultStoreId) {
                    $this->saveConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACTIVE, 0, $store->getId());
                }
            }
        }
        return $this;
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return mixed
     */
    public function getAccessToken($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACCESS_TOKEN, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getExternalBusinessId($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_EXTERNAL_BUSINESS_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getPixelId($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PIXEL_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getPixelAamSettings($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PIXEL_AAM_SETTINGS, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getProfiles($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PROFILES, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getPageId($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_PAGE_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return mixed
     */
    public function getCatalogId($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_CATALOG_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getCommerceAccountId($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_COMMERCE_ACCOUNT_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function isDebugMode($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_DEBUG_MODE, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getApiVersion($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_API_VERSION, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getApiVersionLastUpdate($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_API_VERSION_LAST_UPDATE, $scopeId, $scope);
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function getShippingMethodsMap($storeId = null)
    {
        return [
            'standard' => $this->getConfig(self::XML_PATH_FACEBOOK_SHIPPING_METHODS_STANDARD, $storeId),
            'expedited' => $this->getConfig(self::XML_PATH_FACEBOOK_SHIPPING_METHODS_EXPEDITED, $storeId),
            'rush' => $this->getConfig(self::XML_PATH_FACEBOOK_SHIPPING_METHODS_RUSH, $storeId),
        ];
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return bool
     */
    public function isActiveDailyProductFeed($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_DAILY_PRODUCT_FEED, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return mixed
     */
    public function getFeedUploadMethod($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_FEED_UPLOAD_METHOD, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return mixed
     */
    public function getFeedId($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_FEED_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param string $scope
     * @return mixed
     */
    public function getOffersFeedId($scopeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_OFFERS_FEED_ID, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return mixed
     */
    public function getProductIdentifierAttr($scopeId = null, $scope = null)
    {
        return $this->getConfig(self::XML_PATH_FACEBOOK_PRODUCT_IDENTIFIER, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function isPriceInclTax($scopeId = null, $scope = null)
    {
        return (bool)$this->getConfig(self::XML_PATH_FACEBOOK_PRICE_INCL_TAX, $scopeId, $scope);
    }

    /**
     * @param null $scopeId
     * @param null $scope
     * @return bool
     */
    public function isActiveCollectionsSync($scopeId = null, $scope = null)
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_FACEBOOK_COLLECTIONS_SYNC_IS_ACTIVE, $scopeId = null, $scope = null);
    }
}