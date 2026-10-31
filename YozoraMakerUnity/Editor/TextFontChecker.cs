using System.Collections;
using System.Collections.Generic;
using System.Linq;
using UnityEditor;
using UnityEngine;
using UnityEngine.UI;
using TMPro;

/// <summary>
/// 指定フォントだけ使っているかのチェッカー
/// </summary>
public class TextFontChecker
{
	[MenuItem("Custom/指定フォント以外のテキスト洗い出し")]
	static void Validate()
	{
        // 指定フォント
		List<string> allowedFontNameList = new List<string>() { "testtest", "Arial" };

		// シーン内にある、テキスト系コンポーネント取得
		var textComponents    = UnityEngine.Resources.FindObjectsOfTypeAll(typeof(Text)) as Text[];
        var textProComponents = UnityEngine.Resources.FindObjectsOfTypeAll(typeof(TextMeshProUGUI)) as TextMeshProUGUI[];

		// 許可していないフォントを使っているオブジェクトの洗い出し
		List<Text> denyedTextComponents = new List<Text>();
        foreach(var text in textComponents)
		{
            if(allowedFontNameList.Contains(text.font.name) == false)
			{
				denyedTextComponents.Add(text);
			}
		}

		List<TextMeshProUGUI> denyedTextProComponents = new List<TextMeshProUGUI>();
		foreach (var text in textProComponents)
		{
			if (allowedFontNameList.Contains(text.font.name) == false)
			{
				denyedTextProComponents.Add(text);
			}
		}

		// 該当しなければダイアログを出して終了
		if (denyedTextComponents.Count() == 0 && denyedTextProComponents.Count() == 0)
		{
            TextFontChecker.DrawDialog("問題なし", "編集中シーンでは指定フォントだけ使用されています。");
			return;
		}

		// 該当するものがあれば、パスを記録していく。
		var explain = "編集中シーンで指定フォントを使用していないテキストが見つかりました。";
		foreach (var text in denyedTextComponents)
		{
			explain += "\n" + GetHierarchyPath(text.gameObject.transform);
		}
		foreach (var text in denyedTextProComponents)
		{
			explain += "\n" + GetHierarchyPath(text.gameObject.transform);
		}

        // 該当するオブジェクトのパスをダイアログに表示
        TextFontChecker.DrawDialog("問題あり", explain);

		return;
	}


	static void DrawDialog(string title, string text)
	{
		EditorUtility.DisplayDialog(
				title,
				text,
				"OK"
		);
	}

	static string GetHierarchyPath(Transform self)
	{
		string path = self.gameObject.name;
		Transform parent = self.parent;
		while (parent != null)
		{
			path = parent.name + "/" + path;
			parent = parent.parent;
		}
		return path;
	}
}
