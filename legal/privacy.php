<?php require_once( '../ccs_dash/cms.php' ); ?>
<cms:template title='Privacy Policy' parent='_global_' icon='document' clonable='0' order='8910'>
    <cms:embed 'legal/legal_fields.htm' />
</cms:template>

<cms:ignore>
    Everything below sits OUTSIDE the cms:template block, and that is not a
    style choice. A template block's children run only for a super admin and
    their output is discarded (tags.php:1834, 1963-1965) - which is exactly how
    users/logout.php came to do nothing at all for every ordinary visitor.
    Fields go inside. Page output goes here.
</cms:ignore>
<cms:embed 'legal/legal_layout.htm' />
<?php COUCH::invoke(); ?>
