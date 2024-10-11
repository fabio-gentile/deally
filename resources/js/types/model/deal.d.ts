import { User } from "@/types/model/user"

export interface Deal {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  title: string
  description: string
  slug: string
  original_price?: number | null
  price?: number | null
  expiration_date: string
  start_date: string
  promo_code?: string | null
  deal_url: string
  user_id: number
  category_deal_id: number
  votes: number
  // relations
  images?: ImageDeal[]
  category_deal?: CategoryDeal
  user?: User
  vote_details?: VoteDeal[]
  comments?: CommentDeal[]
}

export interface VoteDeals {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  deal_id: number
  user_id: number
  type: string
  // relations
  deal?: Deal
  user?: User
}

export interface CategoryDeal {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  name: string
  slug: string
  // relations
  deals?: Deal[]
}

export interface CommentDeal {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  deleted_at?: string | null
  deal_id: number
  user_id: number
  parent_id?: number | null
  content: string
  answer_to?: number | null
  // relations
  replies?: CommentDeal[]
  user?: User
  deal?: Deal
  answer_to_user?: User
}

export interface ImageDeal {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  deal_id: number
  filename: string
  original_filename?: string
  path: string
  // relations
  deal?: Deal
}
