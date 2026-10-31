using Zenject;

namespace CoffeeR.Installers
{
    public class ResultInstaller : MonoInstaller
    {
        public int testScore;
        public override void InstallBindings()
        {
            // Container.Bind<Score>().FromInstance(new Score(testScore)).AsCached();
        }
    }
}