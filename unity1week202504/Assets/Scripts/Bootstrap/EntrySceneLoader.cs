using UnityEngine;
using UnityEngine.SceneManagement;

/// <summary>
/// エントリーシーン読み込み
/// </summary>
public class EntrySceneLoader : MonoBehaviour
{    
    [SerializeField, Header("エントリーシーンを自動で読み込む場合に選択")] bool isEntrySceneAutoLoad;

    async void Start()
    {        
        // 通しプレイのように初めはTitleシーンを読み込んでおくとかの場合はTRUEにしてください。
        // あるシーンの状態をデバッグしたい場合はFALSEにして、手動でシーン構成をヒエラルキーで設定してください
        // シーンの単体テストLikeなワークフロー実現のため挟んでます。
        if (isEntrySceneAutoLoad) {
            await SceneManager.LoadSceneAsync("Scenes/Title", LoadSceneMode.Additive);
            SceneManager.SetActiveScene(SceneManager.GetSceneByName("Scenes/Title"));
        }
    }
}
