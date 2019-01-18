<div>
    <h1><{$smarty.const._MD_CHAT_CHAT}></h1>
    <div>
        <div class="chat2">
            <div id="chat_online"><{* Список онлайн *}></div>
        </div>
        <div class="chat1">
            <div id="chat_area"><{* Сообщения *}></div>
        </div>
    </div>

    <{* Форма отправки *}>
    <div id="chat_div_form">
        <table>
            <tr>
                <td>&nbsp;</td>
                <td width="100">
                    <img alt="<{$smarty.const._MD_CHAT_SMILES}>" title="<{$smarty.const._MD_CHAT_SMILES}>"
                         src="<{xoAppUrl /modules/chat/assets/images/chat-smiles.png}>"
                         onclick='openWithSelfMain("<{$xoops_url}>/misc.php?action=showpopups&amp;type=smilies&amp;target=pac_text","smilies",300,475);'>&nbsp;
                    <img title="<{$smarty.const._MD_CHAT_LOADING}>" id="chat_loading"
                         src="<{xoAppUrl /modules/chat/assets/images/ajax-loader.gif}>"
                         alt="<{$smarty.const._MD_CHAT_LOADING}>">
                </td>
            </tr>
            <tr>
                <form id="pac_form" action="">
                    <td>
                        <input type="text" id="pac_text" class="chat-text" value="">
                    </td>
                    <td width="100">
                        <input type="submit" class="chat-submit" value="<{$smarty.const._MD_CHAT_SEND}>">
                    </td>
                </form>
            </tr>
        </table>
    </div>

</div>
