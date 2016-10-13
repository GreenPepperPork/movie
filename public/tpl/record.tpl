<div id="zaor-debug" style="display: block;" class="debug">
    <table cellspacing="0" cellpadding="5" border="1">
        <caption>PROGRAM ERROR</caption>
        <tbody>
            <tr>
                <th>NAME</th>
                <th>MEMORY (byte)</th>
                <th>MESSAGE</th>
                <th>TRACE</th>
            </tr>
            <?php if (!empty($infos['error'])):?>
                <?php foreach($infos['error'] as $info):?>
                <tr>
                    <td><?php echo $info['point']?></td>
                    <td><?php echo $info['memory']?></td>
                    <td>
                        line :    <?php echo $info['info']->getLine()?>
                        &nbsp;
                        message : <?php echo $info['info']->getServerity()?> :<?php echo $info['info']->getMessage()?>
                    </td>
                    <td align="right"><?php echo $info['info']->getTraceAsString()?></td>
                </tr>
                <?php endforeach?>
            <?php endif?>
        </tbody>
    </table>
</div>