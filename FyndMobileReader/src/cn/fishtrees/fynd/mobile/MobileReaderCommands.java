package cn.fishtrees.fynd.mobile;

import javax.microedition.lcdui.Command;

public final class MobileReaderCommands {
	public static final Command CMD_EXIT = new Command("Exit",Command.EXIT,1);
	public static final Command CMD_CONSOLE = new Command("Console",Command.OK,1);
	public static final Command CMD_VIEW = new Command("View", Command.ITEM, 1);
	public static final Command CMD_DIR = new Command("Show Directory",
			Command.OK, 1);
	public static final Command CMD_BACK = new Command("Back", Command.BACK, 1);
	/**
	 * 返回文件列表命令
	 */
	public static final Command CMD_BACKTOLIST = new Command("Go List",
			Command.BACK, 1);
}
