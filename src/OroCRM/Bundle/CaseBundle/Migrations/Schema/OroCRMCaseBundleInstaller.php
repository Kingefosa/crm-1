<?php

namespace OroCRM\Bundle\CaseBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtension;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\CommentBundle\Migration\Extension\CommentExtension;
use Oro\Bundle\CommentBundle\Migration\Extension\CommentExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

use OroCRM\Bundle\CaseBundle\Migrations\Schema\v1_0\OroCRMCaseBundle;
use OroCRM\Bundle\CaseBundle\Migrations\Schema\v1_1\OroCRMCaseBundle as OroCRMCaseBundle11;
use OroCRM\Bundle\CaseBundle\Migrations\Schema\v1_2\OroCRMCaseBundle as OroCRMCaseBundle12;

class OroCRMCaseBundleInstaller implements
    Installation,
    AttachmentExtensionAwareInterface,
    CommentExtensionAwareInterface
{
    /** @var AttachmentExtension */
    protected $attachmentExtension;

    /** @var CommentExtension */
    protected $comment;

    /**
     * {@inheritdoc}
     */
    public function setAttachmentExtension(AttachmentExtension $attachmentExtension)
    {
        $this->attachmentExtension = $attachmentExtension;
    }

    /**
     * @param CommentExtension $commentExtension
     */
    public function setCommentExtension(CommentExtension $commentExtension)
    {
        $this->comment = $commentExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_3';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $migration = new OroCRMCaseBundle();
        $migration->up($schema, $queries);

        $migration11 = new OroCRMCaseBundle11();
        $migration11->up($schema, $queries);

        OroCRMCaseBundle12::addOrganization($schema);

        $this->comment->addCommentAssociation($schema, 'orocrm_case_comment');
        $this->attachmentExtension->addImageRelation(
            $schema,
            'orocrm_case_comment',
            'attachment'
        );
    }
}
