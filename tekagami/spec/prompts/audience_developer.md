# audience: developer

統合仕様候補を、アプリ開発者向けに変換してください。

重視すること:

- endpoint ごとの request/response shape
- SQL flow と write effects
- custom event
- 該当コード
- 移行・改修時に壊しやすい分岐
- 未観測だがコード上存在する分岐

出力では、観測事実・実装事実・推論・要確認を分けてください。
request/response shape は観測根拠がある場合だけ断定してください。shape から値の意味や取得元を推測する場合は「推論」と明記してください。

各分岐には endpoint、pattern id、count、status、custom event、effects、参照コード/DDL を添えてください。count=1 や SQL flow のみの分岐は「弱い候補」「低観測」として扱ってください。
