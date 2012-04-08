# mbstringエミュレータ for Japanese

Original code was written by Andy Matsubara and hosted at SourceForge.JP (until 2006-02-16)

http://sourceforge.jp/projects/mbemulator/


## Special Thanks!
* Andy Matsubara


## 変更履歴

以下は、history.htmlから転記しました。(2012/4/8)

* mbstringエミュレータ for Japanese Ver.0.84（2006/1/23）
	* シフトJISで半角カンマの処理がおかしかったのを修正しました
* mbstringエミュレータ for Japanese Ver.0.83（2005/12/15）
	* mb_send_mailでmb_languageがjaだったときに対応しました
	* mb_send_mailで日本語処理の最後にbreakがなかったので加えました
* mbstringエミュレータ for Japanese Ver.0.82（2005/11/28）
	* mb_languageでパラメータがなかった場合にwarningが出てしまうのを修正しました
	* mb_send_mail内部で_is_encodedを呼び出しているのを_check_encodingを呼ぶように修正しました
* mbstringエミュレータ for Japanese Ver.0.81（2005/10/09）
	* $jis_matchが未定義だったバグを修正しました
* mbstringエミュレータ for Japanese Ver.0.8（2005/8/23）
	* 名称をmbstringエミュレータ for Japaneseに変更しました
	* mb_substitute_characterサポートしました
	* mb_convert_caseサポートしました
	* mb_get_infoサポートしました
	* mb_strtolowerサポートしました
	* mb_strtoupperサポートしました
	* MB_CASE_UPPER，MB_CASE_LOWER，MB_CASE_CASEを定義しました
	* グローバル変数を$mbemu_internalsに集約しました
	* エンコーディングにSHIFT_JIS，ISO-8859-1を追加しました
	* 変換テーブルを必要時に読み込むように修正しました
	* mb_convert_kanaのエンコーディングが指定されていないとき，internal_encodingの値を使うように修正しました
* Ver.0.37（2005/1/30）
	* JISのマッチングのバグを再び修正しました。
* Ver.0.36（2004/11/13）
	* JISのマッチングのバグを修正しました。
* Ver.0.35（2004/9/26）
	* mb_send_mailのバグを修正しまし た。
* Ver.0.341（2004/9/16）
	* mb_convert_kanaのバグを修正し ました。
* Ver.0.34（2004/9/15）
	* mb_convert_kanaのバグを修正し ました。
* Ver.0.33（2004/8/27）
	* mb_encode_numericentity, mb_decode_numericentityをサポートしました。
	* mb_convert_encodingでUTF-16か らの変換をサポートしました。
	* シフトJISの 処理をSJIS-WIN互換にしました。
	* mbstring本家とは異なり，SJISとSJIS-WINの 動作は同じです。
	* mb_strcutの バグを修正しました。
	* 変更履歴をHTMLに変えました。
* Ver.0.32(2004/8/19)
	* mb_http_input, mb_http_output, mb_output_handler をサポートしました
	* ただし，mb_http_inputに関しては文字コー ド変換を行わないため，常にFALSEを返します。
* Ver.0.31(2004/8/16)
	* mb_decode_mimeheaderの バグを修正しました
	* mb_convert_variablesの初 期設定チェックのバグを修正しました
* Ver.0.3(2004/8/8)
	* バグつぶし
	* 入れ子のファンクションで二重宣言になってしまう場合があるので外部に出しました。
	* バグつぶし
	* mb_strcutのバグを修正しました
	* mb_encode_mimeheader, mb_decode_mimeheaderをサポートしました。
	* mb_convert_variablesで配 列からの変換をサポートしました。
	* これに伴いmb_convert_variablesを2パ ターンにし，初期設定で変更できるようにしました。
	* 初期設定ファイルのconvert-variables-arrayonlyがnoの 場合（デフォルト），これまでと同じように配列でなくても変換します。また，最初の要素が配列の場合，それだけを変換します。ただし，こちらの場合，呼び 出し時に引数に&を付けて明示的に参照渡しに する必要があります。
	* convert-variables-arrayonlyがnoで ない場合，変換する引数は配列だけになります。その代わり，&は不要です。
* Ver.0.25(2004/7/7)
	* バグつぶし
	* 入れ子のファンクションで二重宣言になってしまう場合があるので外部に出しました。
* ver.0.24(2004/6/8)
	* mb_strpos, mb_substr_count, mb_preferred_mime_nameをサポートしました。
* ver.0.23(2004/5/19)
	* mb_convert_encodingでUTF-16へ の変換をサポートしました。
	* usage:
		* $str = mb_convert_encoding($str, 'UTF-16', 'EUC-JP, UTF-8');
		* 返すのはBOMなしのビッグ・エンディアンになります。
* ver.0.22(2004/5/11)
	* バグつぶし
	* mb_convert_variablesを 暫定サポート
	* mb_strwidthをサポート
* ver.0.21(2004/4/28)
	* 初期設定をmb-emulator.iniか ら読むように変更
	* EUCとUTF-8間 を直接変換するように変更
	* バグつぶし
* ver.0.2(2004/4/27)
	* jcodeを使わない形で独立しました。12関 数サポート
	* mb_language, mb_internal_encoding, mb_convert_encoding, mb_convert_kana, mb_send_mail, mb_strimwidth, mb_detect_encoding, mb_detect_order, mb_strlen, mb_substr, mb_strcut, mb_strrpos

## 関数サポート状況（2004/8/19）

mbstring関数名 | 今後のサポート予定
---------------------- | -------------------------
mb_convert_case | サポート可能だけど結構大変そう。あまり使いそうにない気がする。もしニーズがあれば
mb_convert_encoding | サポート済み
mb_convert_kana | サポート済みmb_convert_variables | サポート済み
mb_decode_mimeheader | サポート済み
mb_decode_numericentity | サポート済み
mb_detect_encoding | サポート済み
mb_detect_order | サポート済み
mb_encode_mimeheader | サポート済み
mb_encode_numericentity | サポート済み
mb_ereg_match | サポート不可能
mb_ereg_replace | サポート不可能
mb_ereg_search_getpos | サポート不可能
mb_ereg_search_getregs | サポート不可能
mb_ereg_search_init | サポート不可能
mb_ereg_search_pos | サポート不可能
mb_ereg_search_regs | サポート不可能
mb_ereg_search_setpos | サポート不可能
mb_ereg_search | サポート不可能
mb_ereg | サポート不可能
mb_eregi_replace | サポート不可能
mb_eregi | サポート不可能
mb_get_info | サポート可能
mb_http_input | サポート済み（常にFALSEを返す）
mb_http_output | サポート済み
mb_internal_encoding | サポート済み
mb_language | サポート済み（ただし指定しても何も変わらない）
mb_output_handler | サポート済み
mb_parse_str | サポート可能
mb_preferred_mime_name | サポート済み
mb_regex_encoding | サポート不可能
mb_regex_set_options | サポート不可能
mb_send_mail | サポート済み
mb_split | サポート不可能
mb_strcut | サポート済み
mb_strimwidth | サポート済み
mb_strlen | サポート済み
mb_strpos | サポート済み
mb_strrpos | サポート済み
mb_strtolower | サポート可能だけど大変そう
mb_strtoupper | サポート可能だけど大変そう
mb_strwidth | サポート済み
mb_substitute_character | サポート難しそう
mb_substr_count | サポート済み
mb_substr | サポート済み