import "@typespec/http";

using Http;
@service(#{ title: "Item Service", version: "1.0" })
@server("http://localhost:8080")
namespace ItemService;

model Item {
  id: string;
  name: string;
}

@route("/users/{userId}/items")
@tag("Items")
interface Items {
  /** List items */
  @get
  list(@path userId: string): Item[] | Error;
  /** Create item */
  @post
  create(@path userId: string, @body body: Item): Item | Error;
  /** Delete item */
  @delete
  @route("/{itemId}")
  delete(@path userId: string, @path itemId: string): void | Error;
}


@error
model Error {
  code: int64;
  message: string;
}
