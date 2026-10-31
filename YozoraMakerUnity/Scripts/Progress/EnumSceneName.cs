
public enum EnumSceneName
{
    // Sceneファイル上では数値としてシリアライズされるため、
    // 割り振っている数値を変更するとSceneファイル上での意味合いが変わってしまう。
    // 割り振る数値は一度決めたら変更しない方針で運用する(ゲームジャムだからいいよね..)
    Title  = 0,
    Game   = 1,
    Result = 2,
}

public static partial class EnumExtend
{
    public static string GetTypeName(this EnumSceneName param)
    {
        string ret = "";
        switch (param)
        {
            case EnumSceneName.Title:
                ret = "Title";
                break;
            case EnumSceneName.Game:
                ret = "Game";
                break;
            case EnumSceneName.Result:
                ret = "Result";
                break;
        }
        return ret;
    }
}