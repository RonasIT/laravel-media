<?php

namespace RonasIT\Media\Enums;

enum MediaRouteActionEnum: string
{
    case SingleUpload = 'create';
    case Delete = 'delete';
    case Search = 'search';
    case BulkUpload = 'bulk_create';
}
