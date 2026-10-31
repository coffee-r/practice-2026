using UnityEngine;

public class TrekkingDependencyRegister : MonoBehaviour
{
    [SerializeField] AppEnv appEnv;

    void Awake()
    {
        // マスタデータ
        var masterData = ServiceLocator.Instance.Resolve<MasterData>();

        if (ServiceLocator.Instance.Resolve<SceneContext>().Continue == false) {
            // リプレイのために一旦外してます
            ServiceLocator.Instance.UnRegister<Trekking>();
            
            if (appEnv == AppEnv.Production) {
                var sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();
                var trekking = new Trekking(masterData, sceneContext.PackedItems);
                ServiceLocator.Instance.Register<Trekking>(trekking);   
            } else {
                var trekking = new Trekking(masterData, masterData.ItemList);
                ServiceLocator.Instance.Register<Trekking>(trekking);   
            }
        }        
    }

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    // void Awake()
    // {
    //     if (appEnv == AppEnv.Production) {
    //         ServiceLocator.Instance.Register<IScore>(new Score(100));
    //     } else {
    //         ServiceLocator.Instance.Register<IScore>(dummyScore);
    //     }
    // }

    // void OnDestroy()
    // {
    //     ServiceLocator.Instance.UnRegister<IScore>();
    // }
}
