<?php

class Estimate extends Order {
    
    private static $summary_fields = array(
        "ID"        => "#",
        "Status"    => "Status",
        "Total"     => "Total",
        "Created"   => "Created"
    );
    
    public function getCMSFields() {
        
        $existing_customer = $this->config()->existing_customer_class;
        
        // Manually inject HTML for totals as Silverstripe refuses to
        // render Currency.Nice any other way.
        $subtotal_html = '<div id="SubTotal" class="field readonly">';
        $subtotal_html .= '<label class="left" for="Form_ItemEditForm_SubTotal">';
        $subtotal_html .= _t("Orders.SubTotal", "Sub Total");
        $subtotal_html .= '</label>';
        $subtotal_html .= '<div class="middleColumn"><span id="Form_ItemEditForm_SubTotal" class="readonly">';
		$subtotal_html .= $this->SubTotal->Nice();
        $subtotal_html .= '</span></div></div>';
        
        $postage_html = '<div id="Postage" class="field readonly">';
        $postage_html .= '<label class="left" for="Form_ItemEditForm_Postage">';
        $postage_html .= _t("Orders.Postage", "Postage");
        $postage_html .= '</label>';
        $postage_html .= '<div class="middleColumn"><span id="Form_ItemEditForm_Postage" class="readonly">';
		$postage_html .= $this->Postage->Nice();
        $postage_html .= '</span></div></div>';
        
        $tax_html = '<div id="TaxTotal" class="field readonly">';
        $tax_html .= '<label class="left" for="Form_ItemEditForm_TaxTotal">';
        $tax_html .= _t("Orders.Tax", "Tax");
        $tax_html .= '</label>';
        $tax_html .= '<div class="middleColumn"><span id="Form_ItemEditForm_TaxTotal" class="readonly">';
		$tax_html .= $this->TaxTotal->Nice();
        $tax_html .= '</span></div></div>';
        
        $total_html = '<div id="Total" class="field readonly">';
        $total_html .= '<label class="left" for="Form_ItemEditForm_Total">';
        $total_html .= _t("Orders.Total", "Total");
        $total_html .= '</label>';
        $total_html .= '<div class="middleColumn"><span id="Form_ItemEditForm_Total" class="readonly">';
		$total_html .= $this->Total->Nice();
        $total_html .= '</span></div></div>';
        
        $fields = new FieldList(
            $tab_root = new TabSet(
                "Root",
                
                // Main Tab Fields
                $tab_main = new Tab(
                    'Main',
                    
                    // Sidebar
                    OrderSidebar::create(
                        ReadonlyField::create("QuoteNumber", "#")
                            ->setValue($this->ID),
                        LiteralField::create("SubTotal", $subtotal_html),
                        LiteralField::create("Postage", $postage_html),
                        LiteralField::create("TaxTotal", $tax_html),
                        LiteralField::create("Total", $total_html)
                    )->setTitle("Details"),
                    
                    // Items field
                    new OrderItemGridField(
                        "Items",
                        "",
                        $this->Items(),
                        $config = GridFieldConfig::create()
                            ->addComponents(
                                new GridFieldButtonRow('before'),
                                new GridFieldTitleHeader(),
                                new GridFieldEditableColumns(),
                                new GridFieldDeleteAction(),
                                new GridFieldAddOrderItem()
                            )
                    )
                ),
                
                // Main Tab Fields
                $tab_customer = new Tab(
                    'Customer',
                    
                    // Sidebar
                    CustomerSidebar::create(
                        // Items field
                        new GridField(
                            "ExistingCustomers",
                            "",
                            $existing_customer::get(),
                            $config = GridFieldConfig_Base::create()
                                ->addComponents(
                                    $map_extension = new GridFieldMapExistingAction()
                                )
                        )
                    )->setTitle("Use Existing Customer"),
                    
                    TextField::create("Company"),
                    TextField::create("FirstName"),
                    TextField::create("Surname"),
                    TextField::create("Address1"),
                    TextField::create("Address2"),
                    TextField::create("City"),
                    TextField::create("PostCode"),
                    TextField::create("Country"),
                    TextField::create("Email"),
                    TextField::create("PhoneNumber")
                )
            )
        );
        
        // Set the record ID
        $map_extension->setMapFields(array(
            "FirstName",
            "Surname",
            "Email"
        ));
        
        $tab_main->addExtraClass("order-admin-items");
        $tab_customer->addExtraClass("order-admin-customer");

        $this->extend("updateCMSFields", $fields);
        
        return $fields;
    }
    
    public function onBeforeWrite() {
        parent::onBeforeWrite();
        
        $this->Status = $this->config()->default_status;
    } 
    
}
