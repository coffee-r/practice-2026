extends Camera2D

# reference https://2dgames.jp/godot-camera2d-screen-shake/

var time = 0

func _ready():
	position.x = 136 / 2
	position.y = 240 / 2

func _process(delta):
	if time > 0:
		time = max(0, time - delta)
		offset.x = 2 * randf_range(-2, 2) * time
		offset.y = 2 * randf_range(-2, 2) * time

func _on_player_player_death():
	time = 0.3
