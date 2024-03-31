<?php

namespace App\Enums;

use App\Enums\MetaProperties\Description;
use App\Enums\MetaProperties\FeatureGroup;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Values;

#[Meta(Description::class, FeatureGroup::class)]
enum Permission:string {
    use Values;
    use Metadata;
    #[Description("can show all data permissions")] #[FeatureGroup("management - permissions")]
    case MANAGEMENT_PERMISSIONS_SHOW = "management.permissions.show";

    #[Description("can show all data roles")] #[FeatureGroup("management - roles")]
    case MANAGEMENT_ROLES_SHOW = "management.roles.show";
    #[Description("can add new data roles")] #[FeatureGroup("management - roles")]
    case MANAGEMENT_ROLES_STORE = "management.roles.store";
    #[Description("can update data roles by id")] #[FeatureGroup("management - roles")]
    case MANAGEMENT_ROLES_UPDATE = "management.roles.update";
    #[Description("can delete data roles by id")] #[FeatureGroup("management - roles")]
    case MANAGEMENT_ROLES_DESTROY = "management.roles.destroy";
}
