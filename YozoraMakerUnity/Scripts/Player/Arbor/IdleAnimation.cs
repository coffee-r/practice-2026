using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Arbor;

namespace CoffeeR.Player.Arbor
{
    [AddComponentMenu("")]
    public class IdleAnimation : StateBehaviour
    {

        SimpleAnimation simpleAnimation => GetComponent<SimpleAnimation>();
        ShootDirector shootDirector => GetComponentInChildren<ShootDirector>();
        string mode = "DEFAULT";



        public override void OnStateUpdate()
        {
            var nextMode = shootDirector.DirectionName();
            if (nextMode == mode) return;

            mode = nextMode;
            simpleAnimation.Play("IDLE_" + mode);
        }



    }

}

