using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Arbor;

namespace CoffeeR.Player.Arbor
{
    [AddComponentMenu("")]
    public class ShootAnimation : StateBehaviour
    {

        SimpleAnimation simpleAnimation => GetComponent<SimpleAnimation>();
        ShootDirector shootDirector => GetComponentInChildren<ShootDirector>();

        public string prefix;


        public override void OnStateBegin()
        { 
            simpleAnimation.Play(prefix + shootDirector.DirectionName());
        }



    }

}

