/*
 * Fire (Flexible Interface Rendering Engine) is a set of graphics widgets for creating GUIs for j2me applications. 
 * Copyright (C) 2006-2008 Bluevibe (www.bluevibe.net)
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

/**
 * 
 */
package gr.fire.browser.util;

import gr.fire.browser.BlockTag;
import gr.fire.browser.Browser;
import gr.fire.browser.InlineTag;
import gr.fire.browser.ListBlockTag;

/**
 * @author padeler
 *
 */
public final class HtmlUtil
{
	public static void registerHtmlTags(Browser browser)
	{
		{
			Class ie = new InlineTag().getClass();
			
			browser.registerTag(InlineTag.TAG_A,ie);
			browser.registerTag(InlineTag.TAG_B,ie);
			browser.registerTag(InlineTag.TAG_BR,ie);
			browser.registerTag(InlineTag.TAG_EM,ie);
			browser.registerTag(InlineTag.TAG_I,ie);
			browser.registerTag(InlineTag.TAG_IMG,ie);
			browser.registerTag(InlineTag.TAG_SPAN,ie);
			browser.registerTag(InlineTag.TAG_STRONG,ie);
			browser.registerTag(InlineTag.TAG_BIG,ie);
			browser.registerTag(InlineTag.TAG_SMALL,ie);
			browser.registerTag(InlineTag.TAG_TT,ie);
			browser.registerTag(InlineTag.TAG_U,ie);
			browser.registerTag(InlineTag.TAG_TD,ie);
			browser.registerTag(InlineTag.TAG_INPUT,ie);
			browser.registerTag(InlineTag.TAG_BUTTON,ie);
			browser.registerTag(InlineTag.TAG_TEXTAREA,ie);
			browser.registerTag(InlineTag.TAG_CENTER,ie);
			browser.registerTag(InlineTag.TAG_LABEL,ie);
			browser.registerTag(InlineTag.TAG_OPTION,ie);
			browser.registerTag(InlineTag.TAG_SELECT,ie);
		}		
		
		{
			Class be = new BlockTag().getClass();
		
			browser.registerTag(BlockTag.TAG_P,be);		
			browser.registerTag(BlockTag.TAG_BODY,be);		
			browser.registerTag(BlockTag.TAG_TABLE,be);	
			browser.registerTag(BlockTag.TAG_TR,be);
			browser.registerTag(BlockTag.TAG_DIV,be);		
			browser.registerTag(BlockTag.TAG_TITLE,be);		
			browser.registerTag(BlockTag.TAG_META,be);		
			browser.registerTag(BlockTag.TAG_STYLE,be);
			browser.registerTag(BlockTag.TAG_SCRIPT,be);
			browser.registerTag(BlockTag.TAG_H1,be);		
			browser.registerTag(BlockTag.TAG_H2,be);		
			browser.registerTag(BlockTag.TAG_H3,be);		
			browser.registerTag(BlockTag.TAG_H4,be);		
			browser.registerTag(BlockTag.TAG_H5,be);		
			browser.registerTag(BlockTag.TAG_H6,be);
			browser.registerTag(BlockTag.TAG_HR,be);
			browser.registerTag(BlockTag.TAG_FORM,be);
		}		
		
		{
			Class le = new ListBlockTag().getClass();
			browser.registerTag(ListBlockTag.TAG_UL,le);		
			browser.registerTag(ListBlockTag.TAG_LI,le);		
			browser.registerTag(ListBlockTag.TAG_OL,le);		
			browser.registerTag(ListBlockTag.TAG_DL,le);		
			browser.registerTag(ListBlockTag.TAG_DT,le);		
			browser.registerTag(ListBlockTag.TAG_DD,le);		
		}		
	}
}