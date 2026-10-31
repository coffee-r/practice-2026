extends CharacterBody2D

# スコア追加シグナル
signal add_score

# プレイヤー死亡シグナル
signal player_death

# 移動速度
@export var move_speed: float = 25.0

# 星
@export var star_scene : PackedScene

# ヒットエフェクト
@export var hit_scene : PackedScene

# 死亡フラグ
var is_dead: bool = false

# マウス入力された時
func _input(event):
	if is_dead == false && $ShootCoolTimer.is_stopped() && event is InputEventMouseButton && event.is_pressed():
		# 2つのタイマーを起動する
		$ShootWaitTimer.start()
		$ShootCoolTimer.start()
		
		# 回転方向オブジェクトを停止
		$PlayerShootDirector.is_moving = false

func _physics_process(delta):
	# 移動
	var collision: KinematicCollision2D
	if ($ShootWaitTimer.is_stopped()):
		collision = move_and_collide(velocity * delta)
	else:
		collision = move_and_collide(Vector2.ZERO)
	
	# 物体(画面端)と当たったら反射
	if collision:
		velocity = velocity.bounce(collision.get_normal())
	
	# 死亡している場合はプレイヤーと星の速度を0にする
	if is_dead:
		velocity = Vector2.ZERO
		get_tree().call_group("stars", "stop")

func _on_shoot_wait_timer_timeout():
	# スコア追加通知
	emit_add_score()
	
	# 移動の向きを決定する
	var move_direction = $PlayerShootDirector.position.rotated(deg_to_rad(180)).normalized()
		
	# 移動速度を決定する
	velocity = move_direction * move_speed
		
	# 星を発射する
	var star = star_scene.instantiate()
	star.z_index = -1
	star.start($PlayerShootDirector.global_position, $PlayerShootDirector.position.normalized())
	add_sibling(star)
	
	# 回転方向オブジェクトを動かす
	$PlayerShootDirector.is_moving = true

func emit_add_score():
	# 効果音再生
	if $PlayerGrazeArea2D.grazing_count >= 1:
		$GrazeSE.play()
	else:
		$ShootSE.play()

	# 現在の星の数を取得
	var star_count = get_tree().get_nodes_in_group('stars').size()
	
	# スコア追加シグナルを発信する
	print('シグナル add_score')
	emit_signal("add_score", star_count, $PlayerGrazeArea2D.grazing_count)

func _on_player_life_area_2d_body_entered(body):
	# 死亡判定
	is_dead = true
	
	# 発射矢印隠す
	$PlayerShootDirector.hide()
	
	# ヒットエフェクト再生
	var hit_effect = hit_scene.instantiate()
	hit_effect.z_index = -2
	hit_effect.position = body.position
	add_sibling(hit_effect)
	
	# ヒットSE再生
	$DeathSE.play()
	
	# プレイヤー死亡シグナル
	print('シグナル player_death')
	emit_signal('player_death')

func _process(delta):
	# プレイヤーのスプライトアニメーション
	animation()

func animation():
	# 死亡フラグが立っている時
	if is_dead:
		$PlayerAnimatedSprite2D.animation = 'death'
		return
	
	# 星の発射方向の角度を取得
	var degree = rad_to_deg($PlayerShootDirector.position.normalized().angle())
	
	# 角度からアニメーションの向きを決定する
	var direction
	if degree >= -45 && degree < 45:
		direction = 'right'
	elif degree >= 45 && degree < 135:
		direction = 'down'
	elif degree >= -135 && degree < -45:
		direction = 'up'
	else:
		direction = 'left'
	
	# 星の発射待ちの時とそうでない時とでアニメーションを分ける
	if $ShootWaitTimer.is_stopped():
		$PlayerAnimatedSprite2D.animation = 'idle_' + direction
	else:
		$PlayerAnimatedSprite2D.animation = 'shoot_' + direction
