# File for common functionality
import logging
import os

logger = logging.getLogger(__name__)

def get_all_versions():
    """
    Returns a list of all available IATI schema versions, based on the contents 
    of the 'iati-schemas' directory.

    Run `fetch_iati_schemas.sh` to download available IATI schemas.
    
    Returns:
      list of versions found, as strings
    """
    logger.info("get_all_versions() function")
    
    # Get a list of all items in the 'iati-schemas' directory that are not files
    versions = [dir_item for dir_item in os.listdir('iati-schemas') 
       if not os.path.isfile(os.path.join('iati-schemas', dir_item))]

    return sorted(versions)
