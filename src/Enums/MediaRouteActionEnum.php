<?php

namespace RonasIT\Media\Enums;

enum MediaRouteActionEnum: string
{
    case SingleUpload = 'create';

    case Delete = 'delete';

    case Search = 'search';

    case BulkCreate = 'bulk_create';
}