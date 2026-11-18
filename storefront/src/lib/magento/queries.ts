export const GET_PRODUCTS_QUERY = `
  query GetProducts($search: String, $pageSize: Int, $currentPage: Int) {
    products(search: $search, pageSize: $pageSize, currentPage: $currentPage) {
      total_count
      items {
        id
        sku
        name
        url_key
        description {
          html
        }
        short_description {
          html
        }
        stock_status
        price_range {
          minimum_price {
            regular_price {
              value
              currency
            }
            final_price {
              value
              currency
            }
          }
        }
        image {
          url
          label
        }
        media_gallery {
          url
          label
        }
        categories {
          name
          url_path
        }
      }
    }
  }
`;

export const CREATE_EMPTY_CART_MUTATION = `
  mutation CreateEmptyCart {
    createEmptyCart
  }
`;

export const ADD_TO_CART_MUTATION = `
  mutation AddSimpleProductsToCart($cartId: String!, $cartItems: [SimpleProductCartItemInput!]!) {
    addSimpleProductsToCart(input: { cart_id: $cartId, cart_items: $cartItems }) {
      cart {
        total_quantity
        prices {
          grand_total {
            value
            currency
          }
        }
      }
    }
  }
`;
