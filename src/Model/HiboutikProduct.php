<?php

namespace QNeyrat\SyliusHiboutikPlugin\Model;

use Doctrine\Common\Collections\ArrayCollection;

class HiboutikProduct {

    /**
     * @var int
     */
    private int $productId;

    /**
     * @var String
     */
    private string $productModel;

    /**
     * @var String
     */
    private string $productBarcode;

    /**
     * @var Int
     */
    private int $productBrand;

    /**
     * @var Int
     */
    private int $productSupplier;

    /**
     * @var String
     */
    private string $productPrice;

    /**
     * @var String
     */
    private string $productDiscountPrice;

    /**
     * @var String
     */
    private string $productSupplyPrice;

    /**
     * @var Int
     */
    private int $pointsIn;

    /**
     * @var Int
     */
    private int $pointsOut;

    /**
     * @var Int
     */
    private int $productCategory;

    /**
     * @var Int
     */
    private int $productSizeType;

    /**
     * @var Int
     */
    private int $productPackage;

    /**
     * @var Int
     */
    private int $productStockManagement;

    /**
     * @var String
     */
    private string $productSupplierReference;

    /**
     * @var Int
     */
    private int $productVat;

    /**
     * @var Int
     */
    private int $productDisplay;

    /**
     * @var Int
     */
    private int $productDisplayWww;

    /**
     * @var Int
     */
    private int $productArch;

    /**
     * @var String
     */
    private string $productsDesc;

    /**
     * @var String
     */
    private string $productsRefExt;

    /**
     * @var Int
     */
    private int $multiple;

    /**
     * @var \DateTimeInterface
     */
    private \DateTimeInterface $updatedAt;

    /**
     * @var HiboutikSizeDetail[]|ArrayCollection
     */
    private $productSizeDetails;

    /**
     * @var array|ArrayCollection
     */
    private $productSpecificRules;

    /**
     * @var HiboutikImage[]|ArrayCollection
     */
    private $images;

    /**
     * @var array|ArrayCollection
     */
    private $tags;

    /**
     * @var array|ArrayCollection
     */
    private $associatedProducts;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getProductModel(): string
    {
        return $this->productModel;
    }

    /**
     * @param string $productModel
     */
    public function setProductModel(string $productModel): void
    {
        $this->productModel = $productModel;
    }

    /**
     * @return string
     */
    public function getProductBarcode(): string
    {
        return $this->productBarcode;
    }

    /**
     * @param string $productBarcode
     */
    public function setProductBarcode(string $productBarcode): void
    {
        $this->productBarcode = $productBarcode;
    }

    /**
     * @return int
     */
    public function getProductBrand(): int
    {
        return $this->productBrand;
    }

    /**
     * @param int $productBrand
     */
    public function setProductBrand(int $productBrand): void
    {
        $this->productBrand = $productBrand;
    }

    /**
     * @return int
     */
    public function getProductSupplier(): int
    {
        return $this->productSupplier;
    }

    /**
     * @param int $productSupplier
     */
    public function setProductSupplier(int $productSupplier): void
    {
        $this->productSupplier = $productSupplier;
    }

    /**
     * @return string
     */
    public function getProductPrice(): string
    {
        return $this->productPrice;
    }

    /**
     * @param string $productPrice
     */
    public function setProductPrice(string $productPrice): void
    {
        $this->productPrice = $productPrice;
    }

    /**
     * @return string
     */
    public function getProductDiscountPrice(): string
    {
        return $this->productDiscountPrice;
    }

    /**
     * @param string $productDiscountPrice
     */
    public function setProductDiscountPrice(string $productDiscountPrice): void
    {
        $this->productDiscountPrice = $productDiscountPrice;
    }

    /**
     * @return string
     */
    public function getProductSupplyPrice(): string
    {
        return $this->productSupplyPrice;
    }

    /**
     * @param string $productSupplyPrice
     */
    public function setProductSupplyPrice(string $productSupplyPrice): void
    {
        $this->productSupplyPrice = $productSupplyPrice;
    }

    /**
     * @return int
     */
    public function getPointsIn(): int
    {
        return $this->pointsIn;
    }

    /**
     * @param int $pointsIn
     */
    public function setPointsIn(int $pointsIn): void
    {
        $this->pointsIn = $pointsIn;
    }

    /**
     * @return int
     */
    public function getPointsOut(): int
    {
        return $this->pointsOut;
    }

    /**
     * @param int $pointsOut
     */
    public function setPointsOut(int $pointsOut): void
    {
        $this->pointsOut = $pointsOut;
    }

    /**
     * @return int
     */
    public function getProductCategory(): int
    {
        return $this->productCategory;
    }

    /**
     * @param int $productCategory
     */
    public function setProductCategory(int $productCategory): void
    {
        $this->productCategory = $productCategory;
    }

    /**
     * @return int
     */
    public function getProductSizeType(): int
    {
        return $this->productSizeType;
    }

    /**
     * @param int $productSizeType
     */
    public function setProductSizeType(int $productSizeType): void
    {
        $this->productSizeType = $productSizeType;
    }

    /**
     * @return int
     */
    public function getProductPackage(): int
    {
        return $this->productPackage;
    }

    /**
     * @param int $productPackage
     */
    public function setProductPackage(int $productPackage): void
    {
        $this->productPackage = $productPackage;
    }

    /**
     * @return int
     */
    public function getProductStockManagement(): int
    {
        return $this->productStockManagement;
    }

    /**
     * @param int $productStockManagement
     */
    public function setProductStockManagement(int $productStockManagement): void
    {
        $this->productStockManagement = $productStockManagement;
    }

    /**
     * @return string
     */
    public function getProductSupplierReference(): string
    {
        return $this->productSupplierReference;
    }

    /**
     * @param string $productSupplierReference
     */
    public function setProductSupplierReference(string $productSupplierReference): void
    {
        $this->productSupplierReference = $productSupplierReference;
    }

    /**
     * @return int
     */
    public function getProductVat(): int
    {
        return $this->productVat;
    }

    /**
     * @param int $productVat
     */
    public function setProductVat(int $productVat): void
    {
        $this->productVat = $productVat;
    }

    /**
     * @return int
     */
    public function getProductDisplay(): int
    {
        return $this->productDisplay;
    }

    /**
     * @param int $productDisplay
     */
    public function setProductDisplay(int $productDisplay): void
    {
        $this->productDisplay = $productDisplay;
    }

    /**
     * @return int
     */
    public function getProductDisplayWww(): int
    {
        return $this->productDisplayWww;
    }

    /**
     * @param int $productDisplayWww
     */
    public function setProductDisplayWww(int $productDisplayWww): void
    {
        $this->productDisplayWww = $productDisplayWww;
    }

    /**
     * @return int
     */
    public function getProductArch(): int
    {
        return $this->productArch;
    }

    /**
     * @param int $productArch
     */
    public function setProductArch(int $productArch): void
    {
        $this->productArch = $productArch;
    }

    /**
     * @return string
     */
    public function getProductsDesc(): string
    {
        return $this->productsDesc;
    }

    /**
     * @param string $productsDesc
     */
    public function setProductsDesc(string $productsDesc): void
    {
        $this->productsDesc = $productsDesc;
    }

    /**
     * @return string
     */
    public function getProductsRefExt(): string
    {
        return $this->productsRefExt;
    }

    /**
     * @param string $productsRefExt
     */
    public function setProductsRefExt(string $productsRefExt): void
    {
        $this->productsRefExt = $productsRefExt;
    }

    /**
     * @return int
     */
    public function getMultiple(): int
    {
        return $this->multiple;
    }

    /**
     * @param int $multiple
     */
    public function setMultiple(int $multiple): void
    {
        $this->multiple = $multiple;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return ArrayCollection|HiboutikImage[]
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection|HiboutikImage[] $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getProductSpecificRules()
    {
        return $this->productSpecificRules;
    }

    /**
     * @param array|ArrayCollection $productSpecificRules
     */
    public function setProductSpecificRules($productSpecificRules): void
    {
        $this->productSpecificRules = $productSpecificRules;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getAssociatedProducts()
    {
        return $this->associatedProducts;
    }

    /**
     * @param array|ArrayCollection $associatedProducts
     */
    public function setAssociatedProducts($associatedProducts): void
    {
        $this->associatedProducts = $associatedProducts;
    }

    /**
     * @return array|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array|ArrayCollection $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return ArrayCollection|HiboutikSizeDetail[]
     */
    public function getProductSizeDetails()
    {
        return $this->productSizeDetails;
    }

    /**
     * @param ArrayCollection|HiboutikSizeDetail[] $productSizeDetails
     */
    public function setProductSizeDetails($productSizeDetails): void
    {
        $this->productSizeDetails = $productSizeDetails;
    }
}
