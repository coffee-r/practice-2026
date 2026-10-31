using Zenject;

namespace CoffeeR.Installers
{
    public class GameInstaller : MonoInstaller
    {
        public override void InstallBindings()
        {
            Container.Bind<ScoreModel>().AsCached();
            Container.Bind<GrazeNotifier>().AsCached();
        }
    }
}
