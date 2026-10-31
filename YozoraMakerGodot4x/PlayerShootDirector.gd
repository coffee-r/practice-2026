extends Sprite2D

# 回転速度
@export var speed: float = 4.2

# プレイヤーとの距離
@export var distance_to_player: float = 17.0

# 経過した時間のカウント
var delta_count = 0

# 回転させるかどうかのフラグ
var is_moving = true

func _process(delta):
	# 回転させるフラグが立っている時はカウントする
	if is_moving:
		delta_count += delta
	
	# 回転・位置を決定する
	rotation = speed * delta_count
	position = Vector2(1,0).rotated(rotation) * distance_to_player
