<h2>SEO values found in database</h2>

<table>
    <tr>
        <th>Post ID</th>
        <th>Title</th>
        <th>Post type</th>
        <th>Title</th>
        <th>Description</th>
    </tr>

    <?php

    foreach ($importer->data() as $item) {
        ?>
        <tr>
            <td>
                <?= $item->post_id ?>
            </td>

            <td>
                <a href="<?= get_edit_post_link($item->post_id) ?>">
                    <?= $item->post_title ?>
                </a>
            </td>

            <td>
                <?= get_post_type_object($item->post_type)->labels->singular_name ?>
            </td>

            <td>
                <?= $item->seo_title ?>
            </td>

            <td>
                <?= $item->seo_description ?>
            </td>
        </tr>
        <?php
    }

    ?>
</table>
