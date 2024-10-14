import { CommentDeal, Deal } from "@/types/model/deal"
import { CommentDiscussion } from "@/types/model/discussion"
import { Favorite } from "@/types/model/favorite"
import { Social } from "@/types/model/social"

export interface User {
  // columns
  id: number
  name: string
  email?: string
  email_verified_at?: string | null
  password?: string
  remember_token?: string | null
  created_at: string | null
  updated_at: string | null
  biography: string | null
  avatar: string | null
  preferences: Record<string, unknown> | null
  name_updated_at: string | null
  // relations
  deals: Deal[]
  socials: Social[]
  favorites: Favorite[]
  deal_comments: CommentDeal[]
  discussion_comments: CommentDiscussion[]
  notifications: DatabaseNotification[]
}
