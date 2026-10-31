using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Arbor;

namespace CoffeeR.Player.Arbor
{
    [AddComponentMenu("")]
    public class StopMoving : StateBehaviour
    {

        Rigidbody2D playerRigidbody2D => GetComponent<Rigidbody2D>();

        // Use this for initialization
        void Start()
        {

        }


        // OnStateUpdate is called once per frame
        public override void OnStateBegin()
        {
            playerRigidbody2D.velocity = Vector2.zero;
        }

    }

}

