using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Arbor;

namespace CoffeeR.Player.Arbor
{
    [AddComponentMenu("")]
    public class PauseMoving : StateBehaviour
    {

        Rigidbody2D playerRigidbody2D => GetComponent<Rigidbody2D>();
        Vector2 playerBeforeVerocity;

        // Use this for initialization
        void Start()
        {

        }


        // OnStateUpdate is called once per frame
        public override void OnStateBegin()
        {
            playerBeforeVerocity = playerRigidbody2D.velocity;
            playerRigidbody2D.velocity = Vector2.zero;
        }

        // Use this for exit state
        public override void OnStateEnd()
        {
            playerRigidbody2D.velocity = playerBeforeVerocity;
        }

    }

}

