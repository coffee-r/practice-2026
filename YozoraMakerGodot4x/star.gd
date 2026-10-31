extends CharacterBody2D

# 移動の速さ
var move_speed: float = 25.0

func _physics_process(delta):
	# 衝突判定
	var collision = move_and_collide(velocity * delta)
	
	# 星か画面端と当たったら反射
	if collision:
		velocity = velocity.bounce(collision.get_normal())
		$CollisionSE.play()

# プレイヤーから星が作られた時に呼び出し、位置や速度を初期化する
func start(start_position: Vector2, start_move_direction: Vector2):
	position = start_position
	velocity = start_move_direction.normalized() * move_speed

# プレイヤーがゲームオーバーになった時に呼び出し、速度を0にする
func stop():
	velocity = Vector2.ZERO
