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
package cn.fishtrees.fynd.mobile.ui;

import gr.fire.core.BoxLayout;
import gr.fire.core.CommandListener;
import gr.fire.core.Component;
import gr.fire.core.Container;
import gr.fire.core.FireScreen;
import gr.fire.ui.ImageComponent;
import gr.fire.ui.TextComponent;

import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.Displayable;
import javax.microedition.lcdui.Image;

/**
 * 
 * This class is demonstrating the use of the basic UI components of fire2.0 in order to 
 * create a class similar in functionallity to the Fire1.2 Row class.
 *  
 * @author padeler
 *
 */
public class ListItem extends Container implements CommandListener {

    private ImageComponent imgCmp = null;
    private TextComponent txtCmp = null;
    private Object value = null;

    public ListItem(Image img, String txt, boolean imgFirst, int width, int layout) {
        super(new BoxLayout(BoxLayout.X_AXIS));
        int w = width;
        int h = 0;
        if (img != null) {
            imgCmp = new ImageComponent(img, "");
            imgCmp.setLayout(layout);
            imgCmp.setFocusable(false);
            imgCmp.validate();
            h = img.getHeight();
            w -= img.getWidth();
        }
        if (txt != null) {
            txtCmp = new TextComponent(txt, w);
            txtCmp.setLayout(layout);
            txtCmp.validate();
            int th = txtCmp.getContentHeight();
            if (th > h) {
                h = th;
            }
        }

        if (imgFirst) {
            if (imgCmp != null) {
                add(imgCmp);
            }
            if (txtCmp != null) {
                add(txtCmp);
            }
        } else {
            if (txtCmp != null) {
                add(txtCmp);
            }
            if (imgCmp != null) {
                add(imgCmp);
            }
        }
        if (imgCmp != null) {
            imgCmp.setCommandListener(this);
        }
        if (txtCmp != null) {
        	txtCmp.setCommandListener(this);
        }
        this.width = width;
        this.height = h;
        this.setPrefSize(width, h);
    }

    /**
     * Creates a Row with an image followed by text
     *
     * @param img
     * @param txt
     */
    public ListItem(Image img, String txt) {
        this(img, txt, true, FireScreen.getScreen().getWidth(), FireScreen.TOP | FireScreen.LEFT);
    }

    /**
     * Creates a Row with text followed by an image
     *
     * @param img
     * @param txt
     */
    public ListItem(String txt, Image img) {
        this(img, txt, false, FireScreen.getScreen().getWidth(), FireScreen.TOP | FireScreen.LEFT);
    }

    public ListItem(Image img) {
        this(img, null, false, FireScreen.getScreen().getWidth(), FireScreen.TOP | FireScreen.LEFT);
    }

    public ListItem(String txt) {
        this(null, txt, false, FireScreen.getScreen().getWidth(), FireScreen.TOP | FireScreen.LEFT);
    }

    public String getText() {
        return this.txtCmp.getText();
    }

    public void setCommand(Command c) {
        if (txtCmp != null) {
            txtCmp.setCommand(c);
        } else if (imgCmp != null) {
            imgCmp.setCommand(c);
        }
    }

    public void setCommandListener(CommandListener listener) {
    	this.commandListener = listener;
    }

    /**
     * @return the value
     */
    public Object getValue() {
        return value;
    }

    /**
     * @param value The value to set
     */
    public void setValue(Object value) {
        this.value = value;
    }

    /**
     * @param text The text to set
     */
    public void setText(String text) {
        int w = (null == this.imgCmp) ? this.width : (this.width - this.imgCmp.getWidth());
        TextComponent newTextCmp = new TextComponent(text, w);
        newTextCmp.setLayout(layout);
        newTextCmp.validate();
        int th = newTextCmp.getContentHeight();
        int h = (null == this.imgCmp) ? 0 : this.imgCmp.getHeight();
        if (th > h) {
            h = th;
        }
        if (null != this.txtCmp) {
            this.remove(this.txtCmp);
            this.txtCmp = null;
        }
        this.add(newTextCmp);
        newTextCmp.setCommandListener(this);
    }

	public void commandAction(Command cmd, Component c) {
		this.commandListener.commandAction(cmd, this);
		
	}

	public void commandAction(Command arg0, Displayable arg1) {
		// TODO Auto-generated method stub
		
	}
}
